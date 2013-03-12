<?php
namespace KeenIO\Http\Client;

/**
 * Class AdaptorInterface
 *
 * @package KeenIO\Service
 */
interface AdaptorInterface
{
    /**
     * post to the KeenIO API
     *
     * @param $url
     * @param array $parameters
     * @return mixed
     */
    public function doPost($url, array $parameters);
}
