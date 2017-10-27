<?php

namespace KeenIO\Client\Operations\Parameters;

class DefaultParameters
{
    /**
     * Master Key Default Parameters
     * @var array
     */
    public static $masterKey = array(
        ParameterKey::LOCATION => ParameterValueLocation::HEADER,
        ParameterKey::DESCRIPTION => 'The Master API Key.',
        ParameterKey::SENT_AS => 'Authorization',
        ParameterKey::PATTERN => ParameterValuePattern::ALNUM,
        ParameterKey::TYPE => ParameterValueType::STRING,
        ParameterKey::REQUIRED => true
    );

    /**
     * Project ID Default Parameters
     * @var array
     */
    public static $projectId = array(
        ParameterKey::LOCATION => ParameterValueLocation::URI,
        ParameterKey::TYPE => ParameterValueType::STRING
    );

    /**
     * Write Key Default Parameters
     * @var array
     */
    public static $writeKey = array(
        ParameterKey::LOCATION => ParameterValueLocation::HEADER,
        ParameterKey::DESCRIPTION => 'The Write Key for the project.',
        ParameterKey::SENT_AS => 'Authorization',
        ParameterKey::PATTERN => ParameterValuePattern::ALNUM,
        ParameterKey::TYPE => ParameterValueType::STRING,
        ParameterKey::REQUIRED => false
    );

    /**
     * Read Key Default Parameters
     * @var array
     */
    public static $readKey = array(
        ParameterKey::LOCATION => ParameterValueLocation::HEADER,
        ParameterKey::DESCRIPTION => 'The Read Key for the project.',
        ParameterKey::SENT_AS => 'Authorization',
        ParameterKey::PATTERN  => ParameterValuePattern::ALNUM,
        ParameterKey::TYPE => ParameterValueType::STRING,
        ParameterKey::REQUIRED => false
    );

    public static $eventCollectionUri = array(
        ParameterKey::LOCATION    => ParameterValueLocation::URI,
        ParameterKey::DESCRIPTION => 'The event collection.',
        ParameterKey::REQUIRED    => true,
    );

    /**
     * Event Collection Default Parameters
     * @var array
     */
    public static $eventCollectionJson = array(
        ParameterKey::LOCATION    => ParameterValueLocation::JSON,
        ParameterKey::DESCRIPTION => 'The name of the event collection you are analyzing.',
        ParameterKey::TYPE => ParameterValueType::STRING,
        ParameterKey::REQUIRED => true
    );

    /**
     * Access Key Default Parameters
     * @var array
     */
    public static $accessKey = array(
        ParameterKey::LOCATION => ParameterValueLocation::URI,
        ParameterKey::TYPE => ParameterValueType::STRING,
        ParameterKey::REQUIRED => true
    );
}