<?php

namespace Shapecode\FUT\Client\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Client\Common\Plugin\StopwatchPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory;
use Shapecode\FUT\Client\Api\CoreInterface;
use Shapecode\FUT\Client\Authentication\AccountInterface;
use Shapecode\FUT\Client\Config\ConfigInterface;
use Shapecode\FUT\Client\Http\Plugin\ClientCallPlugin;
use Symfony\Component\Stopwatch\Stopwatch;

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
     * @param ConfigInterface     $config
     * @param RequestFactory|null $requestFactory
     */
    public function __construct(ConfigInterface $config, RequestFactory $requestFactory = null)
    {
        $this->config = $config;
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
    }

    /**
     * @inheritdoc
     */
    public function createPluginClient(HttpClient $client, array $plugins = [])
    {
        return new PluginClient($client, $plugins);
    }

    /**
     * @inheritdoc
     */
    protected function createAccountClient(AccountInterface $account, array $options = [])
    {
        $options['http_errors'] = false;
        $options['timeout'] = 5;
        $options['allow_redirects'] = true;

        if ($account->getProxy()) {
            $options['proxy'] = $account->getProxy()->getProxyProtocol();
        }

        if ($account->getCookieJar()) {
            $options['cookies'] = $account->getCookieJar();
        }

        if ($this->getConfig() !== null) {
            $options = array_merge($this->getConfig()->getHttpClientOptions(), $options);
        }

        $stack = HandlerStack::create(new CurlHandler());
        $stack->push(Middleware::retry($this->createRetryHandler()));

        $options['stack'] = $stack;

        $guzzle = new Client($options);

        return new GuzzleAdapter($guzzle);
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
    public function request(AccountInterface $account, $method, $url, array $options = [], array $plugins = [])
    {
        $headers = [];

        if (isset($options['headers'])) {
            $headers = $options['headers'];
            unset($options['headers']);
        }

        $call = new ClientCall();

        $plugins[] = new HeaderSetPlugin(CoreInterface::REQUEST_HEADERS);
        $plugins[] = new HeaderSetPlugin([
            'User-Agent' => $this->getConfig()->getUserAgent(),
        ]);

        if (count($headers)) {
            $plugins[] = new HeaderSetPlugin($headers);
        }

        $plugins[] = new ContentLengthPlugin();
        $plugins[] = new LoggerPlugin($this->getConfig()->getLogger());
        $stopwatch = new Stopwatch();
        $plugins[] = new StopwatchPlugin($stopwatch);
        $plugins[] = new ClientCallPlugin($call);
        $plugins[] = new RedirectPlugin();

        $guzzle = $this->createAccountClient($account, $options);
        $client = $this->createPluginClient($guzzle, $plugins);

        $request = $this->createRequest($method, $url);

        $client->sendRequest($request);

        return $call;
    }

    /**
     * @return ConfigInterface
     */
    protected function getConfig()
    {
        return $this->config;
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
