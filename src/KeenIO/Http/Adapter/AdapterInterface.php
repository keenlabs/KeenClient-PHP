<?php
namespace KeenIO\Http\Adapter;

/**
 * Class AdapterInterface
 *
 * @package KeenIO\Http\Adapter
 */
interface AdapterInterface
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
