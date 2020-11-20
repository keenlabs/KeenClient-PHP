<?php

namespace KeenIO\Client;

use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Client;
use KeenIO\Exception\RuntimeException;

/**
 * Class KeenIOClient
 *
 * @package KeenIO\Client
 *
 * @method array getCollection(string $eventCollection, array $args = array()) {@command KeenIO getCollection}
 * @method array getCollections(array $args = array()) {@command KeenIO getCollections}
 * @method array deleteCollection(array $args = array()) {@command KeenIO getProperty} * 
 * @method array getResources(array $args = array()) {@command KeenIO getResources}
 * @method array getProjects(array $args = array()) {@command KeenIO getProjects}
 * @method array getProject(array $args = array()) {@command KeenIO getProject}
 * @method array getProperty(string $eventCollection, array $args = array()) {@command KeenIO getProperty}
 * @method array getSavedQueries(array $args = array()) {@command KeenIO getProperty}
 * @method array getSavedQuery(array $args = array()) {@command KeenIO getProperty}
 * @method array createSavedQuery(array $args = array()) {@command KeenIO getProperty}
 * @method array deleteSavedQuery(array $args = array()) {@command KeenIO getProperty}
 * @method array getSavedQueryResults(array $args = array()) {@command KeenIO getProperty}
 * @method array getEventSchemas(array $args = array()) {@command KeenIO getEventSchemas}
 * @method array deleteEvents(string $eventCollection, array $args = array()) {@command KeenIO deleteEvents}
 * @method array deleteEventProperties(string $eventCollection, array $args = array()))
 * {@command KeenIO deleteEventProperties}
 * @method array count(string $eventCollection, array $args = array())) {@command KeenIO count}
 * @method array countUnique(string $eventCollection, array $args = array()) {@command KeenIO countUnique}
 * @method array minimum(string $eventCollection, array $args = array()) {@command KeenIO minimum}
 * @method array maximum(string $eventCollection, array $args = array()) {@command KeenIO maximum}
 * @method array average(string $eventCollection, array $args = array()) {@command KeenIO average}
 * @method array sum(string $eventCollection, array $args = array()) {@command KeenIO sum}
 * @method array selectUnique(string $eventCollection, array $args = array()) {@command KeenIO selectUnique}
 * @method array funnel(string $eventCollection, array $args = array()) {@command KeenIO funnel}
 * @method array multiAnalysis(string $eventCollection, array $args = array()) {@command KeenIO multiAnalysis}
 * @method array extraction(string $eventCollection, array $args = array()) {@command KeenIO extraction}
 */
class KeenIOClient extends GuzzleClient
{

    const VERSION = '2.6.0';

    /**
     * Factory to create new KeenIOClient instance.
     *
     * @param array $config
     *
     * @returns \KeenIO\Client\KeenIOClient
     */
    public static function factory($config = array())
    {
        $default = array(
            'masterKey' => null,
            'writeKey'  => null,
            'readKey'   => null,
            'projectId' => null,
            'organizationKey' => null,
            'organizationId' => null,
            'version' => '3.0',
            'headers' => array(
                'Keen-Sdk' => 'php-' . self::VERSION
            )
        );

        // Create client configuration
        $config = self::parseConfig($config, $default);

        $file = 'keen-io-' . str_replace('.', '_', $config['version']) . '.php';

        // Create the new Keen IO Client with our Configuration
        return new self(
            new Client($config),
            new Description(include __DIR__ . "/Resources/{$file}"),
            null,
            function ($arg) {
                return json_decode($arg->getBody(), true);
            },
            null,
            $config
        );
    }

    /**
     * Magic method used to retrieve a command
     *
     * Overridden to allow the `event_collection` parameter to passed separately
     * from the normal argument array.
     *
     * @param string $method Name of the command object to instantiate
     * @param array  $args   Arguments to pass to the command
     *
     * @return mixed Returns the result of the command
     */
    public function __call($method, array $args)
    {
        return parent::__call($method, array($this->combineEventCollectionArgs($args)));
    }

    public function getCommand($name, array $params = [])
    {
        $params['projectId'] = $this->getConfig('projectId');
        $params['masterKey'] = $this->getConfig('masterKey');
        $params['writeKey'] = $this->getKeyForWriting();
        $params['readKey'] = $this->getKeyForReading();
        $params['organizationId'] = $this->getConfig('organizationId');
        $params['organizationKey'] = $this->getConfig('organizationKey');

        return parent::getCommand($name, $params);
    }

    /**
     * Proxy the addEvent command (to be used as a shortcut)
     *
     * @param  string $collection Name of the collection to store events
     * @param  array  $event      Event data to store
     * @return mixed
     */
    public function addEvent($collection, array $event = array())
    {
        $event['event_collection'] = $collection;
        $event['projectId'] = $this->getConfig('projectId');
        $event['writeKey'] = $this->getKeyForWriting();

        $command = parent::getCommand('addEvent', $event);

        return $this->execute($command);
    }

    /**
     * Proxy the addEvents command (to be used as a shortcut)
     *
     * @param  array $events Event data to store
     * @return mixed
     */
    public function addEvents(array $events = array())
    {
        $events['projectId'] = $this->getConfig('projectId');
        $events['writeKey'] = $this->getKeyForWriting();

        $command = parent::getCommand('addEvents', $events);

        return $this->execute($command);
    }

    /**
     * Sets the Project Id used by the Keen IO Client
     *
     * @param string $projectId
     */
    public function setProjectId($projectId)
    {
        $this->setConfig('projectId', $projectId);
    }

    /**
     * Gets the Project Id being used by the Keen IO Client
     *
     * @return string|null Value of the ProjectId or NULL
     */
    public function getProjectId()
    {
        return $this->getConfig('projectId');
    }

    /**
     * Sets the Organization Id used by the Keen IO Client
     *
     * @param string $organizationId
     */
    public function setOrganizationId($organizationId)
    {
        $this->setConfig('organizationId', $organizationId);
    }

    /**
     * Gets the Organization Id being used by the Keen IO Client
     *
     * @return string|null Value of the OrganizationId or NULL
     */
    public function getOrganizationId()
    {
        return $this->getConfig('organizationId');
    }

    /**
     * Sets the API Write Key used by the Keen IO Client
     *
     * @param string $writeKey
     */
    public function setWriteKey($writeKey)
    {
        $this->setConfig('writeKey', $writeKey);
    }

    /**
     * Gets the API Write Key being used by the Keen IO Client
     *
     * @return string|null Value of the WriteKey or NULL
     */
    public function getWriteKey()
    {
        return $this->getConfig('writeKey');
    }

    /**
     * Gets a key which can be used for API Write requests
     *
     * @return string|null Value of the key or NULL
     */
    public function getKeyForWriting()
    {
        return $this->getWriteKey() ?: $this->getMasterKey();
    }

    /**
     * Sets the API Read Key used by the Keen IO Client
     *
     * @param string $readKey
     */
    public function setReadKey($readKey)
    {
        $this->setConfig('readKey', $readKey);
    }

    /**
     * Gets the API Read Key being used by the Keen IO Client
     *
     * @return string|null Value of the ReadKey or NULL
     */
    public function getReadKey()
    {
        return $this->getConfig('readKey');
    }

    /**
     * Gets a key which can be used for API Read requests
     *
     * @return string|null Value of the key or NULL
     */
    public function getKeyForReading()
    {
        return $this->getReadKey() ?: $this->getMasterKey();
    }

    /**
     * Sets the API Master Key used by the Keen IO Client
     *
     * @param string $masterKey
     */
    public function setMasterKey($masterKey)
    {
        $this->setConfig('masterKey', $masterKey);
    }

    /**
     * Gets the API Master Key being used by the Keen IO Client
     *
     * @return string|null Value of the MasterKey or NULL
     */
    public function getMasterKey()
    {
        return $this->getConfig('masterKey');
    }

    /**
     * Sets the API Version used by the Keen IO Client.
     * Changing the API Version will attempt to load a new Service Definition for that Version.
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->setConfig('version', $version);
    }

    /**
     * Gets the Version being used by the Keen IO Client
     *
     * @return string|null Value of the Version or NULL
     */
    public function getVersion()
    {
        return $this->getConfig('version');
    }

    /**
     * ----------------------------------------------------------------------------------------------------
     * SCOPED KEY RELATED METHODS
     * ----------------------------------------------------------------------------------------------------
     */

    /**
     * Get a scoped key for an array of filters
     *
     * @param array  $filters           What filters to encode into a scoped key
     * @param array  $allowedOperations What operations the generated scoped key will allow
     * @return string
     * @throws RuntimeException If no master key is set
     */
    public function createScopedKey($filters, $allowedOperations)
    {
        if (!$masterKey = $this->getMasterKey()) {
            throw new RuntimeException('A master key is needed to create a scoped key');
        }

        $options = array('filters' => $filters);

        if (!empty($allowedOperations)) {
            $options['allowed_operations'] = $allowedOperations;
        }

        $apiKey = pack('H*', $masterKey);

        $opensslOptions = \OPENSSL_RAW_DATA;

        $optionsJson = json_encode($options);

        /**
         * Use the old block size and hex string input if using a legacy master key.
         * Old block size was 32 bytes and old master key was 32 hex characters in length.
         */

        if (strlen($masterKey) == 32) {
            $apiKey = $masterKey;

            // Openssl's built-in PKCS7 padding won't use the 32 bytes block size, so apply it in userland
            // and use OPENSSL zero padding (no-op as already padded)
            $opensslOptions |= \OPENSSL_ZERO_PADDING;
            $optionsJson = $this->padString($optionsJson, 32);
        }

        $cipher = 'AES-256-CBC';

        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = random_bytes($ivLength);

        $encrypted = openssl_encrypt($optionsJson, $cipher, $apiKey, $opensslOptions, $iv);

        $ivHex        = bin2hex($iv);
        $encryptedHex = bin2hex($encrypted);

        $scopedKey = $ivHex . $encryptedHex;

        return $scopedKey;
    }

    /**
     * Implement PKCS7 padding
     *
     * @param string $string
     * @param int    $blockSize
     *
     * @return string
     */
    protected function padString($string, $blockSize = 32)
    {
        $paddingSize = $blockSize - (strlen($string) % $blockSize);
        $string      .= str_repeat(chr($paddingSize), $paddingSize);

        return $string;
    }

    /**
     * Decrypt a scoped key (primarily used for testing)
     *
     * @param  string $scopedKey The scoped Key to decrypt
     * @return mixed
     * @throws RuntimeException If no master key is set
     */
    public function decryptScopedKey($scopedKey)
    {
        if (!$masterKey = $this->getMasterKey()) {
            throw new RuntimeException('A master key is needed to decrypt a scoped key');
        }

        $apiKey = pack('H*', $masterKey);

        $opensslOptions = \OPENSSL_RAW_DATA;
        $paddedManually = false;

        // Use the old hex string input if using a legacy master key
        if (strlen($masterKey) == 32) {
            $apiKey = $masterKey;
            // Openssl's built-in PKCS7 padding won't use the 32 bytes block size, so apply it in userland
            // and use OPENSSL zero padding (no-op as already padded)
            $opensslOptions |= \OPENSSL_ZERO_PADDING;
            $paddedManually = true;
        }

        $cipher = 'AES-256-CBC';

        $ivLength = openssl_cipher_iv_length($cipher) * 2;
        $ivHex    = substr($scopedKey, 0, $ivLength);

        $encryptedHex = substr($scopedKey, $ivLength);

        $result = openssl_decrypt(
            pack('H*', $encryptedHex),
            $cipher,
            $apiKey,
            $opensslOptions,
            pack('H*', $ivHex)
        );

        if ($paddedManually) {
            $result = $this->unpadString($result);
        }

        return json_decode($result, true);
    }

    /**
     * Remove padding for a PKCS7-padded string
     *
     * @param  string $string
     * @return string
     */
    protected function unpadString($string)
    {
        $len = strlen($string);
        $pad = ord($string[$len - 1]);

        return substr($string, 0, $len - $pad);
    }

    /**
     * Attempt to parse config and apply defaults
     *
     * @param  array  $config
     * @param  array  $default
     *
     * @return array Returns the updated config array
     */
    protected static function parseConfig($config, $default)
    {
        array_walk($default, function ($value, $key) use (&$config) {
            if (empty($config[$key]) || !isset($config[$key])) {
                $config[$key] = $value;
            }
        });

        return $config;
    }

    /**
     * Translate a set of args to merge a lone event_collection into
     * an array with the other params
     *
     * @param array $args Arguments to be formatted
     *
     * @return array A single array with event_collection merged in
     * @access private
     */
    private static function combineEventCollectionArgs(array $args)
    {
        $formattedArgs = array();

        if (isset($args[0]) && is_string($args[0])) {
            $formattedArgs['event_collection'] = $args[0];

            if (isset($args[1]) && is_array($args[1])) {
                $formattedArgs = array_merge($formattedArgs, $args[1]);
            }
        } elseif (isset($args[0]) && is_array($args[0])) {
            $formattedArgs = $args[0];
        }

        return $formattedArgs;
    }

    public static function cleanQueryName($raw)
    {
        $filtered = str_replace(' ', '-', $raw);
        $filtered = preg_replace("/[^A-Za-z0-9_\-]/", "", $filtered);

        return $filtered;
    }
}
