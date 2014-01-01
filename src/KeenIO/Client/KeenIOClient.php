<?php

namespace KeenIO\Client;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use RuntimeException;

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
     * @return \KeenIO\Client\KeenIOClient
     */
    public static function factory($config = array())
    {
        $default = array(
            'version'   => '3.0',
            'masterKey' => null,
            'writeKey'  => null,
            'readKey'   => null,
            'projectId' => null
        );

        // Create client configuration
        $config = Collection::fromConfig($config, $default);

        // Because each API Resource uses a separate type of API Key, we need to expose them all in
        // `commands.params`. Doing it this way allows the Service Definitions to set what API Key is used.
        $parameters = array();

        foreach (array('masterKey', 'writeKey', 'readKey') as $key) {
            if ($value = $config->get($key)) {
                $parameters[$key] = $value;
            }
        }

        $config->set('command.params', $parameters);

        // Create the new Keen IO Client with our Configuration
        $client = new self('', $config);

        // Set the Service Definition from the versioned file
        $file = 'keen-io-' . str_replace('.', '_', $client->getConfig('version')) . '.php';
        $client->setDescription(ServiceDescription::factory(__DIR__ . "/Resources/config/{$file}"));

        // Set the content type header to use "application/json" for all requests
        $client->setDefaultOption('headers', array('Content-Type' => 'application/json'));

        return $client;
    }

    /**
     * Magic method used to retrieve a command
     *
     * @param string $method Name of the command object to instantiate
     * @param array  $args   Arguments to pass to the command
     *
     * @return mixed Returns the result of the command
     */
    public function __call($method, $args = array())
    {
        return $this->getCommand($method, isset($args[0]) ? $args[0] : array())->getResult();
    }

    /**
     * Set the Project Id used by the Keen IO Client
     *
     * @param  string $projectId
     * @return void
     */
    public function setProjectId($projectId)
    {
        $this->getConfig()->set('projectId', $projectId);
    }

    /**
     * Get the Project Id being used by the Keen IO Client
     *
     * @return string|null Value of the ProjectId or NULL
     */
    public function getProjectId()
    {
        return $this->getConfig('projectId');
    }

    /**
     * Set the API Write Key used by the Keen IO Client
     *
     * @param  string $writeKey
     * @return void
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
     * Get the API Write Key being used by the Keen IO Client
     *
     * @return string|null Value of the WriteKey or NULL
     */
    public function getWriteKey()
    {
        return $this->getConfig('writeKey');
    }

    /**
     * Set the API Read Key used by the Keen IO Client
     *
     * @param  string $readKey
     * @return void
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
     * Get the API Read Key being used by the Keen IO Client
     *
     * @return string|null Value of the ReadKey or NULL
     */
    public function getReadKey()
    {
        return $this->getConfig('readKey');
    }

    /**
     * Set the API Master Key used by the Keen IO Client
     *
     * @param  string $masterKey
     * @return void
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
     * Get the API Master Key being used by the Keen IO Client
     *
     * @return string|null Value of the MasterKey or NULL
     */
    public function getMasterKey()
    {
        return $this->getConfig('masterKey');
    }

    /**
     * Set the API Version used by the Keen IO Client.
     *
     * Changing the API Version will attempt to load a new Service Definition for that version.
     *
     * @param  string $version
     * @return void
     */
    public function setVersion($version)
    {
        $this->getConfig()->set('version', $version);

        /* Set the Service Definition from the versioned file */
        $file = 'keen-io-' . str_replace('.', '_', $this->getConfig('version')) . '.php';
        $this->setDescription(ServiceDescription::factory(__DIR__ . "/Resources/config/{$file}"));
    }

    /**
     * Get the Version being used by the Keen IO Client
     *
     * @return string|null Value of the Version or NULL
     */
    public function getVersion()
    {
        return $this->getConfig('version');
    }
}
