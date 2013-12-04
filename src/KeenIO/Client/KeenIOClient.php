<?php

namespace KeenIO\Client;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Class KeenIOClient
 *
 * @package KeenIO\Client
 *
 * @method array getResources(array $args = array()) {@command KeenIO getResources}
 * @method array getProjects(array $args = array()) {@command KeenIO getProjects}
 * @method array getProject(array $args = array()) {@command KeenIO getProject}
 * @method array getEventSchemas(array $args = array()) {@command KeenIO getEventSchemas}
 * @method array addEvent(array $args = array()) {@command KeenIO addEvent}
 * @method array addEvents(array $args = array()) {@command KeenIO addEvents}
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
            'baseUrl'   => "https://api.keen.io/{version}/",
            'version'   => '3.0',
            'masterKey' => null,
            'writeKey'  => null,
            'readKey'   => null,
            'projectId' => null
        );

        // Validate the configuration options
        self::validateConfig($config);

        // Create client configuration
        $config = Collection::fromConfig($config, $default);

        /**
         * Because each API Resource uses a separate type of API Key, we need to expose them all in
         * `commands.params`. Doing it this way allows the Service Definitions to set what API Key is used.
         */
        $parameters = array();
        foreach(array('masterKey', 'writeKey', 'readKey') as $key) {
            $parameters[$key] = $config->get($key);
        }
        $config->set('command.params', $parameters);

        // Create the new Keen IO Client with our Configuration
        $client = new self($config->get('baseUrl'), $config);

        // Set the Service Definition from the versioned file
        $file = 'keen-io-' . str_replace('.', '_', $client->getConfig('version')) . '.json';
        $client->setDescription(ServiceDescription::factory(__DIR__ . "/../Resources/config/{$file}"));

        // Set the content type header to use "application/json" for all requests
        $client->setDefaultOption('headers', array('Content-Type' => 'application/json'));

        return $client;
    }

    /**
     * Magic method used to retrieve a command
     * Overriden to allow the `event_collection` parameter to passed separately
     * from the normal argument array.
     *
     * @param string    $method Name of the command object to instantiate
     * @param array $args       Arguments to pass to the command
     *
     * @return mixed Returns the result of the command
     */
    public function __call($method, $args = array())
    {
        if (isset($args[0]) && is_string($args[0])) {
            $args[0] = array('event_collection' => $args[0]);

            if (isset($args[1]) && is_array($args[1]))
                $args[0] = array_merge($args[1], $args[0]);
        }

        return $this->getCommand($method, isset($args[0]) ? $args[0] : array())->getResult();
    }

    /**
     * Get a scoped key for an array of filters
     *
     * @param string    $apiKey            The master API key to use for encryption
     * @param array     $filters           What filters to encode into a scoped key
     * @param array     $allowedOperations What operations the generated scoped key will allow
     * @param int       $source
     *
     * @return string
     */
    public function getScopedKey( $apiKey, $filters, $allowedOperations, $source = MCRYPT_DEV_RANDOM )
    {
        $options = array( 'filters' => $filters );

        if (!empty($allowedOperations)) {
            $options['allowed_operations'] = $allowedOperations;
        }

        $optionsJson = $this->padString(json_encode($options));

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
     * @param   string  $string
     * @param   int     $blockSize
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
     * @param string    $apiKey     The master API key to use for decryption
     * @param string    $scopedKey  The scoped Key to decrypt
     * @return mixed
     */
    public function decryptScopedKey($apiKey, $scopedKey)
    {
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
     * @param string $string
     * @return string
     */
    protected function unpadString($string)
    {
        $len = strlen($string);
        $pad = ord($string[$len - 1]);

        return substr($string, 0, $len - $pad);
    }

    /**
     * Sets the Project Id used by the Keen IO Client
     *
     * @param string $projectId
     */
    public function setProjectId($projectId)
    {
        self::validateConfig(array('projectId' => $projectId));
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
     * Sets the API Write Key used by the Keen IO Client
     *
     * @param string $writeKey
     */
    public function setWriteKey($writeKey)
    {
        self::validateConfig(array('writeKey' => $writeKey));

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
        self::validateConfig(array('readKey' => $readKey));

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
        self::validateConfig(array('masterKey' => $masterKey));

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
        self::validateConfig(array('version' => $version));

        $this->getConfig()->set('version', $version);

        /* Set the Service Definition from the versioned file */
        $file = 'keen-io-' . str_replace('.', '_', $this->getConfig('version')) . '.json';
        $this->setDescription(ServiceDescription::factory(__DIR__ . "/../Resources/config/{$file}"));
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
     * Validates the Keen IO Client configuration options
     *
     * @params  array                     $config
     * @throws  \InvalidArgumentException When a config value does not meet its validation criteria
     */
    static function validateConfig($config = array())
    {
        foreach($config as $option => $value)
        {
            if ($option === 'version' && empty($config['version'])) {
                throw new \InvalidArgumentException("Version can not be empty");
            }

            if ($option === "readKey" && ! ctype_alnum($value)) {
                throw new \InvalidArgumentException( "Read Key '{$value}' contains invalid characters or spaces." );
            }

            if ($option === "writeKey" && ! ctype_alnum($value)) {
                throw new \InvalidArgumentException( "Write Key '{$value}' contains invalid characters or spaces." );
            }

            if ($option === "masterKey" && ! ctype_alnum($value)) {
                throw new \InvalidArgumentException( "Write Key '{$value}' contains invalid characters or spaces." );
            }

            if ($option === "projectId" && ! ctype_alnum($value)) {
                throw new \InvalidArgumentException( "Project ID '{$value}' contains invalid characters or spaces.");
            }
        }
    }
}
