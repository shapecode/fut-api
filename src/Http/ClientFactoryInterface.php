<?php

namespace Shapecode\FUT\Http;

use Shapecode\FUT\Authentication\AccountInterface;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface ClientFactoryInterface
 *
 * @package Shapecode\FUT\Http
 * @author  Shapecode
 */
interface ClientFactoryInterface
{

    /**
     * @param array $options
     *
     * @return ClientInterface
     */
    public function createClient(array $options = []);

    /**
     * @param AccountInterface $account
     * @param array            $options
     *
     * @return ClientInterface
     */
    public function createAccountClient(AccountInterface $account, array $options = []);

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
     * @return mixed|ResponseInterface
     * @throws \Http\Client\Exception
     */
    public function request(AccountInterface $account, $method, $url, array $options = []);
}
