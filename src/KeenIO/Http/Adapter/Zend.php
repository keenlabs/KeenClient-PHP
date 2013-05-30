<?php

namespace KeenIO\Http\Adapter;

use Zend\Http\Client;
use Zend\Http\Request;

/**
 * Class Buzz
 * @package KeenIO\Http\Adapter
 */
final class Zend implements AdapterInterface
{
    private $apiKey;

    /**
     * @param $apiKey
     * @param null $client
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * post to the KeenIO API
     *
     * @param $url
     * @param array $parameters
     * @return mixed
     */
    public function doPost($uri, array $parameters)
    {
        $client = new Client();
        $request = new Request();

        $headers = $request->getHeaders();
        $headers->addHeaderLine('Authorization', $this->apiKey);
        $headers->addHeaderLine('Content-Type', 'application/json');
        $headers->addHeaderLine('X-Powered-By', 'KeenClient-PHP');

        $request->setUri($uri);
        $request->setMethod(Request::METHOD_POST);
        $request->setContent(json_encode($parameters));

        $client->setRequest($request);
        $client->setOptions(array('sslverifypeer' => false));
        $response = $client->dispatch($request);

        if ($response->isSuccess()) {
            return $response->getBody();
        } else {
            return '';
        }
    }
}
