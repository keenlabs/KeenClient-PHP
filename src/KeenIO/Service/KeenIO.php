<?php

namespace KeenIO\Service;

use KeenIO\Http\Client\AdaptorInterface;
use KeenIO\Http\Client\Buzz as BuzzHttpAdaptor;

/**
 * Class KeenIO
 *
 * @package KeenIO\Service
 */
final class KeenIO
{

    private static $projectId;
    private static $apiKey;
    private static $httpAdaptor;

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
     * @return BuzzHttpAdaptor
     */
    public static function getHttpAdaptor()
    {
        if (!self::$httpAdaptor) {
            self::$httpAdaptor = new BuzzHttpAdaptor(self::getApiKey());
        }

        return self::$httpAdaptor;

    }

    /**
     * @param AdaptorInterface $httpAdaptor
     */
    public static function setHttpAdaptor(AdaptorInterface $httpAdaptor)
    {
        self::$httpAdaptor = $httpAdaptor;
    }

    /**
     * @param $projectId
     * @param $apiKey
     */
    public static function configure($projectId, $apiKey)
    {
        self::setProjectId($projectId);
        self::setApiKey($apiKey);
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
        if (!self::getProjectId() or !self::getApiKey()) {
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

        $response = self::getHttpAdaptor()->doPost($url, $parameters);
        $json = json_decode($response);

        return $json->created;
    }
}
