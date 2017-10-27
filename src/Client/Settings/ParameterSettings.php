<?php

namespace KeenIO\Client\Settings;

class ParameterSettings
{
    public static $masterKey = array(
        'location'    => 'header',
        'description' => 'The Master API Key.',
        'sentAs'      => 'Authorization',
        'pattern'     => '/^([[:alnum:]])+$/',
        'type'        => 'string',
        'required'    => true
    );

    public static $projectId = array(
        'location'    => 'uri',
        'type'        => 'string'
    );

    public static $writeKey = array(
        'location'    => 'header',
        'description' => 'The Write Key for the project.',
        'sentAs'      => 'Authorization',
        'pattern'     => '/^([[:alnum:]])+$/',
        'type'        => 'string',
        'required'    => false
    );

    public static $readKey = array(
        'location'    => 'header',
        'description' => 'The Read Key for the project.',
        'sentAs'      => 'Authorization',
        'pattern'     => '/^([[:alnum:]])+$/',
        'type'        => 'string',
        'required'    => false
    );
}