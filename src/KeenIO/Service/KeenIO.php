<?php

namespace KeenIO\Service;

use KeenIO\Http\Adaptor\AdaptorInterface
    , KeenIO\Http\Adaptor\Buzz as BuzzHttpAdaptor
    ;

/**
 * Class KeenIO
 *
 * @package KeenIO\Service
 */
final class KeenIO
{
    private static $projectId;
    private static $writeKey;
    private static $readKey;
    private static $httpAdaptor;

    public static function getWriteKey()
    {
        return self::$writeKey;
    }

    public static function setWriteKey($value)
    {
        if (!ctype_alnum($value)) {
            throw new \Exception(sprintf("Write Key '%s' contains invalid characters or spaces.", $value));
        }

        self::$writeKey = $value;
    }

    public static function getReadKey()
    {
        return self::$readKey;
    }

    public static function setReadKey($value)
    {
        if (!ctype_alnum($value)) {
            throw new \Exception(sprintf("Read Key '%s' contains invalid characters or spaces.", $value));
        }

        self::$readKey = $value;
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
            self::$httpAdaptor = new BuzzHttpAdaptor(self::getWriteKey());
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
     * @param $writeKey
     * @param $readKey
     */
    public static function configure($projectId, $writeKey, $readKey)
    {
        self::setProjectId($projectId);
        self::setWriteKey($writeKey);
        self::setReadKey($readKey);
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
        if (!self::getWriteKey()) {
            throw new \Exception('You must set a Write Key before adding events.');
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

    /**
     * get a scoped key for an array of filters
     *
     * @param $apiKey - the master API key to use for encryption
     * @param $filters - what filters to encode into a scoped key
     * @param $allowed_operations - what operations the generated scoped key will allow
     * @return string
     */
    public static function getScopedKey($apiKey, $filters, $allowed_operations)
    {
        self::validateConfiguration();

        $options = array('filters' => $filters);
        if ($allowed_operations) 
        {
            $options['allowed_operations'] = $allowed_operations;
        }

        $optionsJson = self::padString(json_encode($options));

        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($ivLength);

        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $apiKey, $optionsJson, MCRYPT_MODE_CBC, $iv);

        $ivHex = bin2hex($iv);
        $encryptedHex = bin2hex($encrypted);

        $scopedKey = $ivHex . $encryptedHex;

        return $scopedKey;
    }

    /**
     * decrypt a scoped key (primarily used for testing)
     *
     * @param $apiKey - the master API key to use for decryption
     * @param $scopedKey
     * @return mixed
     */
    public static function decryptScopedKey($apiKey, $scopedKey)
    {
        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC) * 2;
        $ivHex = substr($scopedKey, 0, $ivLength);

        $encryptedHex = substr($scopedKey, $ivLength);

        $resultPadded = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $apiKey,
            pack('H*', $encryptedHex),
            MCRYPT_MODE_CBC,
            pack('H*', $ivHex)
        );

        $result = self::unpadString($resultPadded);

        $options = json_decode($result, true);

        return $options;
    }


    /**
     * implement PKCS7 padding
     *
     * @param $string
     * @param int $blockSize
     * @return string
     */
    protected static function padString($string, $blockSize = 32)
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
    protected static function unpadString($string)
    {
        $len = strlen($string);
        $pad = ord($string[$len - 1]);

        return substr($string, 0, $len - $pad);
    }


    protected static function validateConfiguration()
    {
        // Validate configuration
        if (!self::getProjectId()) {
            throw new \Exception('Keen IO has not been configured');
        }
    }
}
