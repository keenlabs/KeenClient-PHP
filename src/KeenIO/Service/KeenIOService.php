<?php

namespace KeenIO\Service;

use KeenIO\Exception\RuntimeException;
use Zend\Http\Client as HttpClient;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;

/**
 * Thin service layer around the HTTP client
 *
 * You can have shortcut for most methods, and you can also create scoped keys and decrypt them. By
 * default, the service is always up-to-date with latest API version.
 */
class KeenIOService
{
    /**
     * KeenIO API endpoint
     */
    const API_ENDPOINT = 'https://api.keen.io/3.0';

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $masterKey;

    /**
     * @var string
     */
    protected $readKey;

    /**
     * @var string
     */
    protected $writeKey;

    /**
     * @var string
     */
    protected $projectId;

    /**
     * Constructor
     *
     * @param  string $masterKey
     * @param  string $readKey
     * @param  string $writeKey
     * @param  string $projectId
     * @throws RuntimeException
     */
    public function __construct($masterKey = null, $readKey = null, $writeKey = null, $projectId = null)
    {
        if (empty($masterKey) && empty($readKey) && empty($writeKey)) {
            throw new RuntimeException('You need at least a master key, read key and/or write key');
        }

        $this->masterKey = $masterKey;
        $this->readKey   = $readKey;
        $this->writeKey  = $writeKey;
        $this->projectId = $projectId;

        $this->client = new HttpClient();
    }

    /**
     * Set the master key
     *
     * @param  string $masterKey
     * @return void
     */
    public function setMasterKey($masterKey)
    {
        $this->masterKey = (string) $masterKey;
    }

    /**
     * Set the read key
     *
     * @param  string $readKey
     * @return void
     */
    public function setReadKey($readKey)
    {
        $this->readKey = (string) $readKey;
    }

    /**
     * Set the write key
     *
     * @param  string $writeKey
     * @return void
     */
    public function setWriteKey($writeKey)
    {
        $this->writeKey = (string) $writeKey;
    }

    /**
     * Set the project id
     *
     * @param  string $projectId
     * @return void
     */
    public function setProjectId($projectId)
    {
        $this->projectId = (string) $projectId;
    }

    /**
     * ----------------------------------------------------------------------------------------------------
     * RESOURCES AND EVENTS RELATED METHODS
     * ----------------------------------------------------------------------------------------------------
     */

    /**
     * Get the available resources
     *
     * @return array
     */
    public function getResources()
    {
        $response = $this->prepareHttpClient(self::API_ENDPOINT, $this->masterKey)
                         ->send();

        return $this->parseResponse($response);
    }

    /**
     * Get all the projects
     *
     * @return array
     */
    public function getProjects()
    {
        $response = $this->prepareHttpClient(self::API_ENDPOINT . '/projects', $this->masterKey)
                         ->send();

        return $this->parseResponse($response);
    }

    /**
     * Get the current project
     *
     * @return array
     */
    public function getProject()
    {
        $response = $this->prepareHttpClient(self::API_ENDPOINT . "/projects/{$this->projectId}", $this->masterKey)
                         ->send();

        return $this->parseResponse($response);
    }

    /**
     * Get schema information for all event collections in the project
     *
     * @return array
     */
    public function getEventSchemas()
    {
        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/events";
        $response = $this->prepareHttpClient($uri, $this->masterKey)
                         ->send();

        return $this->parseResponse($response);
    }

    /**
     * Add a new event into the named collection
     *
     * @param  string $eventCollection
     * @param  array  $data
     * @return array
     */
    public function addEvent($eventCollection, array $data)
    {
        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/events/{$eventCollection}";
        $response = $this->prepareHttpClient($uri, $this->masterKey ?: $this->writeKey, $data)
                         ->setMethod(HttpRequest::METHOD_POST)
                         ->send();

        return $this->parseResponse($response);
    }

    /**
     * Add multiple events for one or multiple collections
     *
     * Structure must be like this:
     *
     *  [
     *      'event_collection_1' => [
     *          [
     *              'first' => 'event'
     *          ],
     *          [
     *              'second' => 'event'
     *          ]
     *      ],
     *      'event_collection_2' => [
     *          [
     *              'first' => 'event'
     *          ],
     *          [
     *              'second' => 'event'
     *          ]
     *      ]
     *  ]
     *
     * @param  array $data
     * @return array
     */
    public function addEvents(array $data)
    {
        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/events";
        $response = $this->prepareHttpClient($uri, $this->masterKey ?: $this->writeKey, $data)
                         ->setMethod(HttpRequest::METHOD_POST)
                         ->send();

        return $this->parseResponse($response);
    }

    /**
     * Delete events, optionally filtered
     *
     * @param  string $eventCollection
     * @return array
     */
    public function deleteEvents($eventCollection)
    {
        $args = func_get_args();
        $args = isset($args[1]) ? $args[1] : array();

        $parameters = array(
            'filters'   => isset($args['filters']) ? $args['filters'] : null,
            'timeframe' => isset($args['timeframe']) ? $args['timeframe'] : null,
            'timezone'  => isset($args['timezone']) ? $args['timezone'] : null
        );

        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/events/{$eventCollection}";
        $response = $this->prepareHttpClient($uri, $this->masterKey, $parameters)
                         ->setMethod(HttpRequest::METHOD_DELETE)
                         ->send();

        return $this->parseResponse($response);
    }

    /**
     * Delete properties by name for all events in a collection
     *
     * @param  string $eventCollection
     * @param  string $property
     * @return array
     */
    public function deleteEventProperties($eventCollection, $property)
    {
        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/events/{$eventCollection}/properties/{$property}";
        $response = $this->prepareHttpClient($uri, $this->masterKey, array())
                         ->setMethod(HttpRequest::METHOD_DELETE)
                         ->send();

        return $this->parseResponse($response);
    }

    /**
     * ----------------------------------------------------------------------------------------------------
     * ANALYTICS RELATED METHODS
     * ----------------------------------------------------------------------------------------------------
     */

    /**
     * Do a count operation
     *
     * @param  string $eventCollection
     * @return array
     */
    public function count($eventCollection)
    {
        $args = func_get_args();
        $args = isset($args[1]) ? $args[1] : array();

        $parameters = array(
            'event_collection' => $eventCollection,
            'filters'          => isset($args['filters']) ? json_encode($args['filters']) : null,
            'timeframe'        => isset($args['timeframe']) ? $args['timeframe'] : null,
            'interval'         => isset($args['interval']) ? $args['interval'] : null,
            'timezone'         => isset($args['timezone']) ? $args['timezone'] : null
        );

        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/queries/count";
        $response = $this->prepareHttpClient($uri, $this->readKey ?: $this->masterKey)
                         ->setParameterGet(array_filter($parameters))
                         ->send();

        $response = $this->parseResponse($response);

        return $response['result'];
    }

    /**
     * Do a count unique operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @return array
     */
    public function countUnique($eventCollection, $targetProperty)
    {
        $args = func_get_args();
        $args = isset($args[2]) ? $args[2] : array();

        $parameters = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => isset($args['filters']) ? json_encode($args['filters']) : null,
            'timeframe'        => isset($args['timeframe']) ? $args['timeframe'] : null,
            'interval'         => isset($args['interval']) ? $args['interval'] : null,
            'timezone'         => isset($args['timezone']) ? $args['timezone'] : null,
            'group_by'         => isset($args['group_by']) ? json_encode($args['group_by']) : null
        );

        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/queries/count_unique";
        $response = $this->prepareHttpClient($uri, $this->readKey ?: $this->masterKey)
                         ->setParameterGet(array_filter($parameters))
                         ->send();

        $response = $this->parseResponse($response);

        return $response['result'];
    }

    /**
     * Do a minimum operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @return array
     */
    public function minimum($eventCollection, $targetProperty)
    {
        $args = func_get_args();
        $args = isset($args[2]) ? $args[2] : array();

        $parameters = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => isset($args['filters']) ? json_encode($args['filters']) : null,
            'timeframe'        => isset($args['timeframe']) ? $args['timeframe'] : null,
            'interval'         => isset($args['interval']) ? $args['interval'] : null,
            'timezone'         => isset($args['timezone']) ? $args['timezone'] : null,
            'group_by'         => isset($args['group_by']) ? json_encode($args['group_by']) : null
        );

        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/queries/minimum";
        $response = $this->prepareHttpClient($uri, $this->readKey ?: $this->masterKey)
                         ->setParameterGet(array_filter($parameters))
                         ->send();

        $response = $this->parseResponse($response);

        return $response['result'];
    }

    /**
     * Do a maximum operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @return array
     */
    public function maximum($eventCollection, $targetProperty)
    {
        $args = func_get_args();
        $args = isset($args[2]) ? $args[2] : array();

        $parameters = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => isset($args['filters']) ? json_encode($args['filters']) : null,
            'timeframe'        => isset($args['timeframe']) ? $args['timeframe'] : null,
            'interval'         => isset($args['interval']) ? $args['interval'] : null,
            'timezone'         => isset($args['timezone']) ? $args['timezone'] : null,
            'group_by'         => isset($args['group_by']) ? json_encode($args['group_by']) : null
        );

        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/queries/maximum";
        $response = $this->prepareHttpClient($uri, $this->readKey ?: $this->masterKey)
                         ->setParameterGet(array_filter($parameters))
                         ->send();

        $response = $this->parseResponse($response);

        return $response['result'];
    }

    /**
     * Do an average operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @return array
     */
    public function average($eventCollection, $targetProperty)
    {
        $args = func_get_args();
        $args = isset($args[2]) ? $args[2] : array();

        $parameters = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => isset($args['filters']) ? json_encode($args['filters']) : null,
            'timeframe'        => isset($args['timeframe']) ? $args['timeframe'] : null,
            'interval'         => isset($args['interval']) ? $args['interval'] : null,
            'timezone'         => isset($args['timezone']) ? $args['timezone'] : null,
            'group_by'         => isset($args['group_by']) ? json_encode($args['group_by']) : null
        );

        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/queries/average";
        $response = $this->prepareHttpClient($uri, $this->readKey ?: $this->masterKey)
                         ->setParameterGet(array_filter($parameters))
                         ->send();

        $response = $this->parseResponse($response);

        return $response['result'];
    }

    /**
     * Do a sum operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @return array
     */
    public function sum($eventCollection, $targetProperty)
    {
        $args = func_get_args();
        $args = isset($args[2]) ? $args[2] : array();

        $parameters = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => isset($args['filters']) ? json_encode($args['filters']) : null,
            'timeframe'        => isset($args['timeframe']) ? $args['timeframe'] : null,
            'interval'         => isset($args['interval']) ? $args['interval'] : null,
            'timezone'         => isset($args['timezone']) ? $args['timezone'] : null,
            'group_by'         => isset($args['group_by']) ? json_encode($args['group_by']) : null
        );

        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/queries/sum";
        $response = $this->prepareHttpClient($uri, $this->readKey ?: $this->masterKey)
                         ->setParameterGet(array_filter($parameters))
                         ->send();

        $response = $this->parseResponse($response);

        return $response['result'];
    }

    /**
     * Do a select unique operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @return array
     */
    public function selectUnique($eventCollection, $targetProperty)
    {
        $args = func_get_args();
        $args = isset($args[2]) ? $args[2] : array();

        $parameters = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => isset($args['filters']) ? json_encode($args['filters']) : null,
            'timeframe'        => isset($args['timeframe']) ? $args['timeframe'] : null,
            'interval'         => isset($args['interval']) ? $args['interval'] : null,
            'timezone'         => isset($args['timezone']) ? $args['timezone'] : null
        );

        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/queries/select_unique";
        $response = $this->prepareHttpClient($uri, $this->readKey ?: $this->masterKey)
                         ->setParameterGet(array_filter($parameters))
                         ->send();

        $response = $this->parseResponse($response);

        return $response['result'];
    }

    /**
     * Do a funnel operation
     *
     * @param  array $steps
     * @return array
     */
    public function funnel(array $steps)
    {
        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/queries/funnel";
        $response = $this->prepareHttpClient($uri, $this->readKey ?: $this->masterKey)
                         ->setParameterGet(array_filter(json_encode($steps)))
                         ->send();

        $response = $this->parseResponse($response);

        return $response['result'];
    }

    /**
     * Do a multi analysis operation
     *
     * @param  string $eventCollection
     * @param  array  $analysis
     * @return array
     */
    public function multiAnalysis($eventCollection, array $analysis)
    {
        $args = func_get_args();
        $args = isset($args[2]) ? $args[2] : array();

        $parameters = array(
            'event_collection' => $eventCollection,
            'analyses'         => json_encode($analysis),
            'filters'          => isset($args['filters']) ? json_encode($args['filters']) : null,
            'timeframe'        => isset($args['timeframe']) ? $args['timeframe'] : null,
            'interval'         => isset($args['interval']) ? $args['interval'] : null,
            'timezone'         => isset($args['timezone']) ? $args['timezone'] : null
        );

        $uri      = self::API_ENDPOINT . "/projects/{$this->projectId}/queries/multi_analysis";
        $response = $this->prepareHttpClient($uri, $this->readKey ?: $this->masterKey)
                         ->setParameterGet(array_filter($parameters))
                         ->send();

        $response = $this->parseResponse($response);

        return $response['result'];
    }

    /**
     * ----------------------------------------------------------------------------------------------------
     * SCOPED KEY RELATED METHODS
     * ----------------------------------------------------------------------------------------------------
     */

    /**
     * Create a scoped key for an array of filters
     *
     * @param  array  $filters           What filters to encode into a scoped key
     * @param  array  $allowedOperations What operations the generated scoped key will allow
     * @param  int    $source
     * @return string
     * @throws RuntimeException
     */
    public function createScopedKey(array $filters, array $allowedOperations, $source = MCRYPT_DEV_RANDOM)
    {
        if (null === $this->masterKey) {
            throw new RuntimeException('A master key is needed to create a scoped key');
        }

        $options = array('filters' => $filters);

        if (!empty($allowedOperations)) {
            $options['allowed_operations'] = $allowedOperations;
        }

        $optionsJson = $this->padString(json_encode($options));

        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv       = mcrypt_create_iv($ivLength, $source);

        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->masterKey, $optionsJson, MCRYPT_MODE_CBC, $iv);

        $ivHex        = bin2hex($iv);
        $encryptedHex = bin2hex($encrypted);

        $scopedKey = $ivHex . $encryptedHex;

        return $scopedKey;
    }

    /**
     * Decrypt a scoped key (primarily used for testing)
     *
     * @param  string $scopedKey The scoped Key to decrypt
     * @return string
     * @throws RuntimeException
     */
    public function decryptScopedKey($scopedKey)
    {
        if (null === $this->masterKey) {
            throw new RuntimeException('A master key is needed to create a scoped key');
        }

        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC) * 2;
        $ivHex    = substr($scopedKey, 0, $ivLength);

        $encryptedHex = substr($scopedKey, $ivLength);

        $resultPadded = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $this->masterKey,
            pack('H*', $encryptedHex),
            MCRYPT_MODE_CBC,
            pack('H*', $ivHex)
        );

        return json_decode($this->unpadString($resultPadded), true);
    }

    /**
     * Implement PKCS7 padding
     *
     * @param  string $string
     * @param  int    $blockSize
     * @return string
     */
    private function padString($string, $blockSize = 32)
    {
        $paddingSize = $blockSize - (strlen($string) % $blockSize);
        $string      .= str_repeat(chr($paddingSize), $paddingSize);

        return $string;
    }

    /**
     * Remove padding for a PKCS7-padded string
     *
     * @param  string $string
     * @return string
     */
    private function unpadString($string)
    {
        $len = strlen($string);
        $pad = ord($string[$len - 1]);

        return substr($string, 0, $len - $pad);
    }

    /**
     * ----------------------------------------------------------------------------------------------------
     * HTTP CLIENT
     * ----------------------------------------------------------------------------------------------------
     */

    /**
     * Prepare the HTTP client
     *
     * @param  string $uri
     * @param  string $key
     * @param  array  $parameters
     * @return HttpClient
     */
    private function prepareHttpClient($uri, $key, array $parameters = array())
    {
        $this->client->resetParameters()
                     ->setUri($uri)
                     ->setRawBody(json_encode($parameters))
                     ->getRequest()
                     ->getHeaders()
                     ->addHeaderLine('Content-Type', 'application/json')
                     ->addHeaderLine('Authorization', $key);

        return $this->client;
    }

    /**
     * @param  HttpResponse $response
     * @return array
     */
    private function parseResponse(HttpResponse $response)
    {
        $body = json_decode($response->getBody(), true);

        // @TODO: handle error

        return $body;
    }
}
