<?php

namespace KeenIO\Service;

use Zend\ServiceManager\ServiceManager;

use Zend\EventManager\EventManager;
use Zend\EventManager\StaticEventManager;

use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Json\Json;

use Zend\I18n\Validator\Alnum as Alnum;

abstract class AbstractService {

    private $eventManager;
    private $projectId;
    private $apiKey;
    private $name;

    public function __construct($apiKey)
    {
        $this->setApiKey($apiKey);
    }

    public function getEventManager()
    {
        if (!$this->eventManager instanceof Events) {
            $this->setEventManager(new EventManager(array(
                __CLASS__,
                get_called_class(),
            )));
        }
        return $this->eventManager;
    }

    public function setEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
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
        $this->name = $name;
        return $this;
    }

    protected function verifyCollectionName($collectionName)
    {
        $validator = new Alnum();
        if (!$validator->isValid($collectionName))
            throw new \Exception("Collection name '$collection' contains invalid characters or spaces.");
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

    public function projects() {
        $http = $this->getHttpClient();
        $http->setUri('https://api.keen.io/3.0/projects');
        $http->getMethod('GET');

        return $this->sendHttpRequest($http);
    }
}