<?php
namespace KeenIO\Service;

/**
 * Class KeenIO
 *
 * @package KeenIO\Service
 */
final class KeenIO
{

    private static $projectId;
    private static $apiKey;

    /** @var AdaptorInterface */
    private static $adaptor;

    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * @param $value
     * @throws \Exception
     */
    public static function setApiKey($value)
    {
        if (!ctype_alnum($value)) {
            throw new \Exception(sprintf("API Key '%s' contains invalid characters or spaces.", $value));
        }

        self::$apiKey = $value;
    }

    public static function getProjectId()
    {
        return self::$projectId;
    }

    /**
     * @param $value
     * @throws \Exception
     */
    public static function setProjectId($value)
    {
        // Validate collection name
        if (!ctype_alnum($value)) {
            throw new \Exception(
                "Project ID '" . $value . "' contains invalid characters or spaces."
            );
        }

        self::$projectId = $value;
    }

    /**
     * @param $projectId
     * @param $apiKey
     * @param AdaptorInterface $adaptor
     */
    public static function configure($projectId, $apiKey, AdaptorInterface $adaptor = null)
    {
        self::setProjectId($projectId);
        self::setApiKey($apiKey);
        self::setAdaptor($adaptor);
    }

    /**
     * @param AdaptorInterface $adaptor
     */
    public static function setAdaptor(AdaptorInterface $adaptor = null)
    {
        if ($adaptor === null) {
            $adaptor = new BuzzHttpAdaptor(self::getApiKey());
        }

        self::$adaptor = $adaptor;
    }

    /**
     * add an event to KeenIO
     *
     * @param $collectionName
     * @param $parameters
     * @return mixed
     * @throws \Exception
     */
    public static function addEvent($collectionName, $parameters = array())
    {
        // Validate configuration
        if (!self::getProjectId() or !self::getApiKey() or !self::$adaptor) {
            throw new \Exception('Keen IO has not been configured');
        }

        if (!ctype_alnum($collectionName)) {
            throw new \Exception(
                sprintf("Collection name '%s' contains invalid characters or spaces.", $collectionName)
            );
        }

        $url = sprintf(
            'https://api.keen.io/3.0/projects/%s/events/%s',
            self::getProjectId(),
            $collectionName
        );

        $response = self::$adaptor->doPost($url, $parameters);
        $json = json_decode($response);

        return $json->created;
    }
}
