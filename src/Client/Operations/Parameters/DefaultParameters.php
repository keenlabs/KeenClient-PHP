<?php

namespace KeenIO\Client\Operations\Parameters;

class DefaultParameters
{
    /**
     * Master Key Default Parameters
     * @var array
     */
    public static $masterKey = array(
        ParameterKey::LOCATION => ValueLocation::HEADER,
        ParameterKey::DESCRIPTION => 'The Master API Key.',
        ParameterKey::SENT_AS => 'Authorization',
        ParameterKey::PATTERN => ValuePattern::ALNUM,
        ParameterKey::TYPE => ValueType::TYPE_STRING,
        ParameterKey::REQUIRED => true
    );

    /**
     * Project ID Default Parameters
     * @var array
     */
    public static $projectId = array(
        ParameterKey::LOCATION => ValueLocation::URI,
        ParameterKey::TYPE => ValueType::TYPE_STRING
    );

    /**
     * Write Key Default Parameters
     * @var array
     */
    public static $writeKey = array(
        ParameterKey::LOCATION => ValueLocation::HEADER,
        ParameterKey::DESCRIPTION => 'The Write Key for the project.',
        ParameterKey::SENT_AS => 'Authorization',
        ParameterKey::PATTERN => ValuePattern::ALNUM,
        ParameterKey::TYPE => ValueType::TYPE_STRING,
        ParameterKey::REQUIRED => false
    );

    /**
     * Read Key Default Parameters
     * @var array
     */
    public static $readKey = array(
        ParameterKey::LOCATION => ValueLocation::HEADER,
        ParameterKey::DESCRIPTION => 'The Read Key for the project.',
        ParameterKey::SENT_AS => 'Authorization',
        ParameterKey::PATTERN  => ValuePattern::ALNUM,
        ParameterKey::TYPE => ValueType::TYPE_STRING,
        ParameterKey::REQUIRED => false
    );

    public static $eventCollectionUri = array(
        ParameterKey::LOCATION    => ValueLocation::URI,
        ParameterKey::DESCRIPTION => 'The event collection.',
        ParameterKey::REQUIRED    => true,
    );

    /**
     * Event Collection Default Parameters
     * @var array
     */
    public static $eventCollectionJson = array(
        ParameterKey::LOCATION    => ValueLocation::JSON,
        ParameterKey::DESCRIPTION => 'The name of the event collection you are analyzing.',
        ParameterKey::TYPE => ValueType::TYPE_STRING,
        ParameterKey::REQUIRED => true
    );

    /**
     * Access Key Default Parameters
     * @var array
     */
    public static $accessKey = array(
        ParameterKey::LOCATION => ValueLocation::URI,
        ParameterKey::TYPE => ValueType::TYPE_STRING,
        ParameterKey::REQUIRED => true
    );
}