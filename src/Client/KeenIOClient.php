<?php

namespace KeenIO\Client;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use KeenIO\Exception\RuntimeException;

/**
 * Class KeenIOClient
 *
 * @package KeenIO\Client
 *
 * @method array getCollection(array $args = array()) {@command KeenIO getCollection}
 * @method array getCollections(array $args = array()) {@command KeenIO getCollections}
 * @method array getResources(array $args = array()) {@command KeenIO getResources}
 * @method array getProjects(array $args = array()) {@command KeenIO getProjects}
 * @method array getProject(array $args = array()) {@command KeenIO getProject}
 * @method array getProperty(array $args = array()) {@command KeenIO getProperty}
 * @method array getSavedQueries(array $args = array()) {@command KeenIO getProperty}
 * @method array getSavedQuery(array $args = array()) {@command KeenIO getProperty}
 * @method array createSavedQuery(array $args = array()) {@command KeenIO getProperty}
 * @method array deleteSavedQuery(array $args = array()) {@command KeenIO getProperty}
 * @method array getSavedQueryResults(array $args = array()) {@command KeenIO getProperty}
 * @method array getEventSchemas(array $args = array()) {@command KeenIO getEventSchemas}
 * @method array deleteEvents(array $args = array()) {@command KeenIO deleteEvents}
 * @method array deleteEventProperties(array $args = array()) {@command KeenIO deleteEventProperties}
 * @method array count(array $args = array()) {@command KeenIO count}
 * @method array countUnique(array $args = array()) {@command KeenIO countUnique}
 * @method array minimum(array $args = array()) {@command KeenIO minimum}
 * @method array maximum(array $args = array()) {@command KeenIO maximum}
 * @method array average(array $args = array()) {@command KeenIO average}
 * @method array sum(array $args = array()) {@command KeenIO sum}
 * @method array selectUnique(array $args = array()) {@command KeenIO selectUnique}
 * @method array funnel(array $args = array()) {@command KeenIO funnel}
 * @method array multiAnalysis(array $args = array()) {@command KeenIO multiAnalysis}
 * @method array extraction(array $args = array()) {@command KeenIO extraction}
 */
class KeenIOClient extends Client
{
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
            'baseUrl'   => 'https://api.keen.io/{version}/',
            'version'   => '3.0',
            'masterKey' => null,
            'writeKey'  => null,
            'readKey'   => null,
            'projectId' => null,
            'organizationKey' => null,
            'organizationId' => null
        );

        // Create client configuration
        $config = self::parseConfig($config, $default);
        $config = Collection::fromConfig($config, $default);

        // Because each API Resource uses a separate type of API Key, we need to expose them all in
        // `commands.params`. Doing it this way allows the Service Definitions to set what API Key is used.
        $parameters = array();
        foreach (array('masterKey', 'writeKey', 'readKey', 'organizationKey') as $key) {
            $parameters[$key] = $config->get($key);
        }

        $config->set('command.params', $parameters);

        // Create the new Keen IO Client with our Configuration
        $client = new self($config->get('baseUrl'), $config);

        // Set the Service Definition from the versioned file
        $file = 'keen-io-' . str_replace('.', '_', $client->getConfig('version')) . '.php';
        $client->setDescription(ServiceDescription::factory(__DIR__ . "/Resources/{$file}"));

        // Set the content type header to use "application/json" for all requests
        $client->setDefaultOption('headers', array('Content-Type' => 'application/json'));

        return $client;
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
    public function __call($method, $args)
    {
        if (isset($args[0]) && is_string($args[0])) {
            $args[0] = array('event_collection' => $args[0]);

            if (isset($args[1]) && is_array($args[1])) {
                $args[0] = array_merge($args[1], $args[0]);
            }
        }

        return $this->getCommand($method, isset($args[0]) ? $args[0] : array())->getResult();
    }

    /**
     * Proxy the addEvent command (to be used as a shortcut)
     *
     * @param  string $collection Name of the collection to store events
     * @param  array  $event      Event data to store
     * @return mixed
     */
    public function addEvent($collection, $event = array())
    {
        return $this->getCommand('addEvent', array(
            'event_collection' => $collection,
            'event_data'       => $event
        ))->getResult();
    }

    /**
     * Proxy the addEvents command (to be used as a shortcut)
     *
     * @param  array $events Event data to store
     * @return mixed
     */
    public function addEvents($events = array())
    {
        return $this->getCommand('addEvents', array('event_data' => $events))->getResult();
    }

    /**
     * Sets the Project Id used by the Keen IO Client
     *
     * @param string $projectId
     */
    public function setProjectId($projectId)
    {
        $this->getConfig()->set('projectId', $projectId);
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
        $this->getConfig()->set('organizationId', $organizationId);
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
        $this->getConfig()->set('writeKey', $writeKey);

        // Add API Read Key to `command.params`
        $params             = $this->getConfig('command.params');
        $params['writeKey'] = $writeKey;

        $this->getConfig()->set('command.params', $params);
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
     * Sets the API Read Key used by the Keen IO Client
     *
     * @param string $readKey
     */
    public function setReadKey($readKey)
    {
        $this->getConfig()->set('readKey', $readKey);

        // Add API Read Key to `command.params`
        $params            = $this->getConfig('command.params');
        $params['readKey'] = $readKey;

        $this->getConfig()->set('command.params', $params);
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
     * Sets the API Master Key used by the Keen IO Client
     *
     * @param string $masterKey
     */
    public function setMasterKey($masterKey)
    {
        $this->getConfig()->set('masterKey', $masterKey);

        // Add API Master Key to `command.params`
        $params              = $this->getConfig('command.params');
        $params['masterKey'] = $masterKey;

        $this->getConfig()->set('command.params', $params);
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
        $this->getConfig()->set('version', $version);

        /* Set the Service Definition from the versioned file */
        $file = 'keen-io-' . str_replace('.', '_', $this->getConfig('version')) . '.php';
        $this->setDescription(ServiceDescription::factory(__DIR__ . "/Resources/{$file}"));
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
     * @param int    $source
     * @return string
     * @throws RuntimeException If no master key is set
     */
    public function createScopedKey($filters, $allowedOperations, $source = MCRYPT_DEV_URANDOM)
    {
        if (!$masterKey = $this->getMasterKey()) {
            throw new RuntimeException('A master key is needed to create a scoped key');
        }

        $options = array('filters' => $filters);

        if (!empty($allowedOperations)) {
            $options['allowed_operations'] = $allowedOperations;
        }

        $apiKey = pack('H*', $masterKey);
        $blockSize = 16;

        /**
         * Use the old block size and hex string input if using a legacy master key.
         * Old block size was 32 bytes and old master key was 32 hex characters in length.
         */

        if (strlen($masterKey) == 32) {
            $apiKey = $masterKey;
            $blockSize = 32;
        }

        $optionsJson = $this->padString(json_encode($options), $blockSize);

        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv       = mcrypt_create_iv($ivLength, $source);

        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $apiKey, $optionsJson, MCRYPT_MODE_CBC, $iv);

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

        // Use the old hex string input if using a legacy master key
        if (strlen($masterKey) == 32) {
            $apiKey = $masterKey;
        }

        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC) * 2;
        $ivHex    = substr($scopedKey, 0, $ivLength);

        $encryptedHex = substr($scopedKey, $ivLength);

        $resultPadded = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $apiKey,
            pack('H*', $encryptedHex),
            MCRYPT_MODE_CBC,
            pack('H*', $ivHex)
        );

        return json_decode($this->unpadString($resultPadded), true);
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

    public static function cleanQueryName($raw)
    {
        $filtered = str_replace(' ', '-', $raw);
        $filtered = preg_replace("/[^A-Za-z0-9_\-]/", "", $filtered);

        return $filtered;
    }
}
