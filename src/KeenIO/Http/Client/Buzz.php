<?php

namespace KeenIO\Http\Client;

use Buzz\Browser;

/**
 * Class Buzz
 * @package KeenIO\Http\Client
 */
final class Buzz implements AdaptorInterface
{

    private $apiKey;
    private $browser;

    /**
     * @param $apiKey
     * @param null $client
     */
    public function __construct($apiKey, $client = null)
    {
        $this->apiKey = $apiKey;

        if (!$client) {
            $client = new Browser();
        }

        $this->browser = $client;
    }

    /**
     * post to the KeenIO API
     *
     * @param $url
     * @param array $parameters
     * @return mixed
     */
    public function doPost($url, array $parameters)
    {
        $headers = array(
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json'
        );

        $content = json_encode($parameters);

        $response = $this->browser->post($url, $headers, $content);

        return $response->getContent();
    }
}
