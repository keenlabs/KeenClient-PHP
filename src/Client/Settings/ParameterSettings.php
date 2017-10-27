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
}