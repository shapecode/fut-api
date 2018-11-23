<?php

namespace Shapecode\FUT\Client\Http;

use Shapecode\FUT\Client\Api\CoreInterface;
use Shapecode\FUT\Client\Authentication\AccountInterface;
use Shapecode\FUT\Client\Config\ConfigInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory;

/**
 * Class ClientFactory
 *
 * @package Shapecode\FUT\Client\Http
 * @author  Shapecode
 */
class ClientFactory implements ClientFactoryInterface
{

    /** @var RequestFactory */
    protected $requestFactory;

    /** @var ConfigInterface */
    protected $config;

    const MAX_RETRIES = 4;

    /**
     * @param RequestFactory $requestFactory
     */
    public function __construct(RequestFactory $requestFactory = null)
    {
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
    }

    /**
     * @inheritdoc
     */
    public function createClient(array $options = [])
    {
        if ($this->getConfig() !== null) {
            $options = array_merge($this->getConfig()->getHttpClientOptions(), $options);
        }

        $stack = HandlerStack::create(new CurlHandler());
        $stack->push(Middleware::retry($this->createRetryHandler()));

        $options['stack'] = $stack;

        return new Client($options);
    }

    /**
     * @inheritdoc
     */
    public function createAccountClient(AccountInterface $account, array $options = [])
    {
        $options['http_errors'] = false;
        $options['timeout'] = 5;
        $options['allow_redirects'] = true;
        $options['headers'] = CoreInterface::REQUEST_HEADERS;

        if ($account->getProxy()) {
            $options['proxy'] = $account->getProxy()->getProxyProtocol();
        }

        if ($account->getCookieJar()) {
            $options['cookies'] = $account->getCookieJar();
        }

        return $this->createClient($options);
    }

    /**
     * @inheritdoc
     */
    public function createRequest($method, $uri, $body = null, array $headers = [])
    {
        return $this->requestFactory->createRequest($method, $uri, $headers, $body);
    }

    /**
     * @inheritdoc
     */
    public function request(AccountInterface $account, $method, $url, array $options = [])
    {
        $client = $this->createAccountClient($account);

        return $client->request($method, $url, $options);
    }

    /**
     * @return ConfigInterface
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param ConfigInterface $config
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @return RequestFactory
     */
    protected function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @param RequestFactory $requestFactory
     */
    public function setRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * @return \Closure
     */
    protected function createRetryHandler()
    {
        return function (
            $retries,
            Psr7Request $request,
            Psr7Response $response = null,
            RequestException $exception = null
        ) {
            if ($retries >= self::MAX_RETRIES) {
                return false;
            }

            if (!($this->isServerError($response) || $this->isConnectError($exception))) {
                return false;
            }

            return true;
        };
    }

    /**
     * @param Psr7Response $response
     *
     * @return bool
     */
    protected function isServerError(Psr7Response $response = null)
    {
        return $response && $response->getStatusCode() >= 500;
    }

    /**
     * @param RequestException $exception
     *
     * @return bool
     */
    protected function isConnectError(RequestException $exception = null)
    {
        return $exception instanceof ConnectException;
    }
}
