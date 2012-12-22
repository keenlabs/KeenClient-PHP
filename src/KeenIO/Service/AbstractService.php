<?php

namespace KeenIO\Service;

use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Json\Json;

use Zend\I18n\Validator\Alnum as Alnum;

abstract class AbstractService {

    protected $apiKey;
    protected $name;

    public function __construct($apiKey)
    {
        $this->setApiKey($apiKey);
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($value)
    {
        $this->apiKey = $value;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $validator = new Alnum();
        if (!$validator->isValid($name))
            throw new \Exception("Keen IO name '$name' contains invalid characters or spaces.");

        $this->name = $name;
        return $this;
    }

    protected function validateOptions($allowed, $options)
    {
        foreach ($options as $key => $value) {
            if (!in_array($key, $allowed))
                throw new \Exception("$key is not an allowed option");
        }
    }

    protected function getHttpClient() {
        $http = new Client();

        $http->setOptions(array('sslverifypeer' => false));
        $headers = new Headers();
        $headers->addHeaderLine('Authorization', $this->getApiKey());
        $headers->addHeaderLine('Content-Type', 'application/json');
        $http->setHeaders($headers);

        return $http;
    }

    protected function sendHttpRequest(Client $http) {
        $response = $http->send();
        return Json::decode($response->getBody());
    }
}