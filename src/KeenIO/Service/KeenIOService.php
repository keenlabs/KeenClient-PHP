<?php

namespace KeenIO\Service;

use KeenIO\Client\KeenIOClient;
use RuntimeException;

/**
 * Thin service layer around the HTTP client
 *
 * You can have shortcut for most methods, and you can also create scoped keys and decrypt them. By
 * default, the service is always up-to-date with latest API version.
 */
class KeenIOService
{
    /**
     * @var KeenIOClient
     */
    protected $keenIOClient;

    /**
     * @param KeenIOClient $keenIOClient
     */
    public function __construct(KeenIOClient $keenIOClient)
    {
        $this->keenIOClient = $keenIOClient;
    }

    /**
     * Get the available resources
     *
     * @return array
     */
    public function getResources()
    {
        return $this->keenIOClient->getResources();
    }

    /**
     * Get all the projects
     *
     * @return array
     */
    public function getProjects()
    {
        return $this->keenIOClient->getProjects();
    }

    /**
     * Get the current project
     *
     * @return array
     */
    public function getProject()
    {
        return $this->keenIOClient->getProject();
    }

    /**
     * Get schema information for all event collections in the project
     *
     * @return array
     */
    public function getEventSchemas()
    {
        return $this->keenIOClient->getEventSchemas();
    }

    /**
     * Add a new event into the named collection
     *
     * @param  string $eventCollection
     * @param  array $data
     * @return array
     */
    public function addEvent($eventCollection, array $data)
    {
        return $this->keenIOClient->addEvent(array(
            'event_collection' => $eventCollection,
            'data'             => $data
        ));
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
     * @param  array $eventsData
     * @return array
     */
    public function addEvents(array $eventsData)
    {
        return $this->keenIOClient->addEvents(array('data' => $eventsData));
    }

    /**
     * Delete events, optionally filtered
     *
     * @param  string $eventCollection
     * @param  array  $filters
     * @param  string $timeframe
     * @param  string $timezone
     * @return array
     */
    public function deleteEvents($eventCollection, array $filters = array(), $timeframe = '', $timezone = '')
    {
        $payload = array(
            'event_collection' => $eventCollection,
            'filters'          => $filters,
            'timeframe'        => $timeframe,
            'timezone'         => $timezone
        );

        return $this->keenIOClient->deleteEvents(array_filter($payload));
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
        return $this->keenIOClient->deleteEventProperties(array(
            'event_collection' => $eventCollection,
            'property_name'    => $property
        ));
    }

    /**
     * Do a count operation
     *
     * @param  string $eventCollection
     * @param  array  $filters
     * @param  string $timeframe
     * @param  string $interval
     * @param  string $timezone
     * @param  array  $groupBy
     * @return array
     */
    public function count($eventCollection, array $filters = array(), $timeframe = '', $interval = '',
                          $timezone = '', array $groupBy = array())
    {
        $payload = array(
            'event_collection' => $eventCollection,
            'filters'          => $filters,
            'timeframe'        => $timeframe,
            'interval'         => $interval,
            'timezone'         => $timezone,
            'group_by'         => $groupBy
        );

        return $this->keenIOClient->count(array_filter($payload));
    }

    /**
     * Do a count unique operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @param  array  $filters
     * @param  string $timeframe
     * @param  string $interval
     * @param  string $timezone
     * @param  array  $groupBy
     * @return array
     */
    public function countUnique($eventCollection, $targetProperty, array $filters = array(), $timeframe = '',
                                $interval = '', $timezone = '', array $groupBy = array())
    {
        $payload = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => $filters,
            'timeframe'        => $timeframe,
            'interval'         => $interval,
            'timezone'         => $timezone,
            'group_by'         => $groupBy
        );

        return $this->keenIOClient->countUnique(array_filter($payload));
    }

    /**
     * Do a minimum operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @param  array  $filters
     * @param  string $timeframe
     * @param  string $interval
     * @param  string $timezone
     * @param  array  $groupBy
     * @return array
     */
    public function minimum($eventCollection, $targetProperty, array $filters = array(), $timeframe = '',
                            $interval = '', $timezone = '', array $groupBy = array())
    {
        $payload = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => $filters,
            'timeframe'        => $timeframe,
            'interval'         => $interval,
            'timezone'         => $timezone,
            'group_by'         => $groupBy
        );

        return $this->keenIOClient->minimum(array_filter($payload));
    }

    /**
     * Do a maximum operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @param  array  $filters
     * @param  string $timeframe
     * @param  string $interval
     * @param  string $timezone
     * @param  array  $groupBy
     * @return array
     */
    public function maximum($eventCollection, $targetProperty, array $filters = array(), $timeframe = '',
                            $interval = '', $timezone = '', array $groupBy = array())
    {
        $payload = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => $filters,
            'timeframe'        => $timeframe,
            'interval'         => $interval,
            'timezone'         => $timezone,
            'group_by'         => $groupBy
        );

        return $this->keenIOClient->maximum(array_filter($payload));
    }

    /**
     * Do an average operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @param  array  $filters
     * @param  string $timeframe
     * @param  string $interval
     * @param  string $timezone
     * @param  array  $groupBy
     * @return array
     */
    public function average($eventCollection, $targetProperty, array $filters = array(), $timeframe = '',
                            $interval = '', $timezone = '', array $groupBy = array())
    {
        $payload = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => $filters,
            'timeframe'        => $timeframe,
            'interval'         => $interval,
            'timezone'         => $timezone,
            'group_by'         => $groupBy
        );

        return $this->keenIOClient->average(array_filter($payload));
    }

    /**
     * Do a sum operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @param  array  $filters
     * @param  string $timeframe
     * @param  string $interval
     * @param  string $timezone
     * @param  array  $groupBy
     * @return array
     */
    public function sum($eventCollection, $targetProperty, array $filters = array(), $timeframe = '',
                        $interval = '', $timezone = '', array $groupBy = array())
    {
        $payload = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => $filters,
            'timeframe'        => $timeframe,
            'interval'         => $interval,
            'timezone'         => $timezone,
            'group_by'         => $groupBy
        );

        return $this->keenIOClient->sum(array_filter($payload));
    }

    /**
     * Do a select unique operation
     *
     * @param  string $eventCollection
     * @param  string $targetProperty
     * @param  array  $filters
     * @param  string $timeframe
     * @param  string $interval
     * @param  string $timezone
     * @param  array  $groupBy
     * @return array
     */
    public function selectUnique($eventCollection, $targetProperty, array $filters = array(), $timeframe = '',
                                 $interval = '', $timezone = '', array $groupBy = array())
    {
        $payload = array(
            'event_collection' => $eventCollection,
            'target_property'  => $targetProperty,
            'filters'          => $filters,
            'timeframe'        => $timeframe,
            'interval'         => $interval,
            'timezone'         => $timezone,
            'group_by'         => $groupBy
        );

        return $this->keenIOClient->selectUnique(array_filter($payload));
    }

    /**
     * Do a funnel operation
     *
     * @param  array $steps
     * @return array
     */
    public function funnel(array $steps)
    {
        return $this->keenIOClient->funnel(array('steps' => $steps));
    }

    /**
     * Do a multi analysis operation
     *
     * @param  string $eventCollection
     * @param  array  $analysis
     * @param  array  $filters
     * @param  string $timeframe
     * @param  string $interval
     * @param  string $timezone
     * @param  array  $groupBy
     * @return array
     */
    public function multiAnalysis($eventCollection, array $analysis, array $filters = array(), $timeframe = '',
                                  $interval = '', $timezone = '', array $groupBy = array())
    {
        $payload = array(
            'event_collection' => $eventCollection,
            'analysis'         => $analysis,
            'filters'          => $filters,
            'timeframe'        => $timeframe,
            'interval'         => $interval,
            'timezone'         => $timezone,
            'group_by'         => $groupBy
        );

        return $this->keenIOClient->multiAnalysis(array_filter($payload));
    }

    /**
     * Create a scoped key for an array of filters
     *
     * @param array  $filters           What filters to encode into a scoped key
     * @param array  $allowedOperations What operations the generated scoped key will allow
     * @param int    $source
     * @return string
     * @throws RuntimeException
     */
    public function createScopedKey(array $filters, array $allowedOperations, $source = MCRYPT_DEV_RANDOM)
    {
        $masterKey = $this->keenIOClient->getMasterKey();

        if (null === $masterKey) {
            throw new RuntimeException('A master key is needed to create a scoped key');
        }

        $options = array('filters' => $filters);

        if (!empty($allowedOperations)) {
            $options['allowed_operations'] = $allowedOperations;
        }

        $optionsJson = $this->padString(json_encode($options));

        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv       = mcrypt_create_iv($ivLength, $source);

        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $masterKey, $optionsJson, MCRYPT_MODE_CBC, $iv);

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
        $masterKey = $this->keenIOClient->getMasterKey();

        if (null === $masterKey) {
            throw new RuntimeException('A master key is needed to create a scoped key');
        }

        $ivLength = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC) * 2;
        $ivHex    = substr($scopedKey, 0, $ivLength);

        $encryptedHex = substr($scopedKey, $ivLength);

        $resultPadded = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $masterKey,
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
}
