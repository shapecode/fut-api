<?php

namespace Shapecode\FUT\Client\Http;

use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Shapecode\FUT\Client\Authentication\AccountInterface;

/**
 * Interface ClientFactoryInterface
 *
 * @package Shapecode\FUT\Client\Http
 * @author  Shapecode
 */
interface ClientFactoryInterface
{

    /**
     * @param HttpClient $client
     * @param array      $options
     *
     * @return PluginClient
     */
    public function createPluginClient(HttpClient $client, array $options = []);

    /**
     * @param       $method
     * @param       $uri
     * @param null  $body
     * @param array $headers
     *
     * @return RequestInterface
     */
    public function createRequest($method, $uri, $body = null, array $headers = []);

    /**
     * @param AccountInterface $account
     * @param                  $method
     * @param                  $url
     * @param array            $options
     *
     * @return ClientCall
     */
    public function request(AccountInterface $account, $method, $url, array $options = []);
}
