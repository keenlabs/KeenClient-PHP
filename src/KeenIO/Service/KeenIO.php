<?php

namespace KeenIO\Service;

use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Json\Json;
use Zend\I18n\Validator\Alnum as Alnum;

static final class KeenIO {

    static private $projectId;
    static private $apiKey;

    static public function getApiKey()
    {
        return self::apiKey;
    }

    static public function setApiKey($value)
    {
        // Validate collection name
        $validator = new Alnum();
        if (!$validator->isValid($value))
            throw new \Exception("API Key '$value' contains invalid characters or spaces.");

        self::apiKey = $value;
        return self;
    }

    static public function getProjectId()
    {
        return self::projectId;
    }

    static public function setProjectId($value)
    {
        // Validate collection name
        $validator = new Alnum();
        if (!$validator->isValid($value))
            throw new \Exception("Project ID '$name' contains invalid characters or spaces.");

        self::projectId = $value
        return self;
    }

    static public function configure($projectId, $apiKey)
    {
        self::setProjectId($projectId);
        self::setApiKey($apiKey);
    }

    static public function addEvent($collectionName, $parameters)
    {
        // Validate configuration
        if (!self::getProjectId() or !self::getApiKey())
            throw new \Exception('Keen IO has not been configured');

        // Validate collection name
        $validator = new Alnum();
        if (!$validator->isValid($name))
            throw new \Exception("Collection name '$name' contains invalid characters or spaces.");

        $http = new Client();

        $http->setOptions(array('sslverifypeer' => false));
        $headers = new Headers();
        $headers->addHeaderLine('Authorization', self::getApiKey());
        $headers->addHeaderLine('Content-Type', 'application/json');
        $http->setHeaders($headers);

        $http->setUri('https://api.keen.io/3.0/projects/' . self::getProjectId() . '/events/' . $collectionName);
        $http->setMethod('POST');
        $http->getRequest()->setContent(Json::encode($parameters));

        $response = $http->send();
        $json = Json::decode($response->getBody());

        return $json->created;
    }
}