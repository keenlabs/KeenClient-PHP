<?php

namespace KeenIO\Service;

use KeenIO\Http\Adaptor\AdaptorInterface;
use KeenIO\Http\Adaptor\Buzz as BuzzHttpAdaptor;

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
        self::validateConfiguration();

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

    /**
     * get a scoped key for an array of filters
     *
     * note: requires libmcrypt
     *
     * @param $filters
     * @return string
     */
    public static function getScopedKey($filters)
    {
        self::validateConfiguration();

        $theFilters = array('filters' => $filters);
        $filter_json = self::padString(json_encode($theFilters));

        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($ivLength);

        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, self::getApiKey(), $filter_json, MCRYPT_MODE_CBC, $iv);

        $ivHexed = bin2hex($iv);
        $encryptedHexed = bin2hex($encrypted);

        $scopedKey = $ivHexed . $encryptedHexed;

        return $scopedKey;
    }

    /**
     * decrypt a scoped key (primarily used for testing)
     *
     * @param $scopedKey
     * @return mixed
     */
    public static function decryptScopedKey($scopedKey)
    {
        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC) * 2;
        $ivHexed = substr($scopedKey, 0, $ivLength);

        $encryptedHexed = substr($scopedKey, $ivLength);

        $resultPadded = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            self::getApiKey(),
            pack('H*', $encryptedHexed),
            MCRYPT_MODE_CBC,
            pack('H*', $ivHexed)
        );

        $result = self::unpadString($resultPadded);

        $theFilters = json_decode($result, true);

        return $theFilters['filters'];
    }


    /**
     * implement PKCS7 padding
     *
     * @param $string
     * @param int $blockSize
     * @return string
     */
    public static function padString($string, $blockSize = 32)
    {
        $paddingSize = $blockSize - (strlen($string) % $blockSize);
        $string .= str_repeat(chr($paddingSize), $paddingSize);

        return $string;
    }

    /**
     * remove padding for a PKCS7-padded string
     *
     * @param $string
     * @return string
     */
    public static function unpadString($string)
    {
        $len = strlen($string);
        $pad = ord($string[$len - 1]);

        return substr($string, 0, $len - $pad);
    }


    protected static function validateConfiguration()
    {
        // Validate configuration
        if (!self::getProjectId() or !self::getApiKey()) {
            throw new \Exception('Keen IO has not been configured');
        }
    }
}
