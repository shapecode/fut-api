<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http;

use Closure;
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
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Shapecode\FUT\Client\Api\CoreInterface;
use Shapecode\FUT\Client\Authentication\AccountInterface;
use Shapecode\FUT\Client\Config\ConfigInterface;
use Shapecode\FUT\Client\Http\Plugin\ClientCallPlugin;
use Symfony\Component\Stopwatch\Stopwatch;
use function count;

class ClientFactory implements ClientFactoryInterface
{
    /** @var RequestFactoryInterface */
    protected $requestFactory;

    /** @var StreamFactoryInterface */
    protected $streamFactory;

    /** @var UriFactoryInterface */
    protected $urlFactory;

    /** @var ConfigInterface */
    protected $config;

    /** @var CookieJarBuilderInterface */
    protected $cookieJarBuilder;

    /** @var LoggerInterface */
    protected $logger;

    public const MAX_RETRIES = 4;

    public function __construct(
        ConfigInterface $config,
        ?CookieJarBuilderInterface $cookieJarBuilder = null,
        ?LoggerInterface $logger = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        ?UriFactoryInterface $urlFactory = null
    ) {
        $this->config           = $config;
        $this->cookieJarBuilder = $cookieJarBuilder ?: new CookieJarBuilder();
        $this->logger           = $logger ?: new NullLogger();

        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory  = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
        $this->urlFactory     = $urlFactory ?: Psr17FactoryDiscovery::findUrlFactory();
    }

    /**
     * @inheritdoc
     */
    public function request(
        AccountInterface $account,
        string $method,
        string $url,
        array $options = [],
        array $plugins = []
    ) : ClientCall {
        $headers = [];

        if (isset($options['headers'])) {
            /** @var mixed[] $headers */
            $headers = $options['headers'];
            unset($options['headers']);
        }

        $call = new ClientCall();

        $plugins[] = new HeaderSetPlugin(CoreInterface::REQUEST_HEADERS);
        $plugins[] = new HeaderSetPlugin([
            'User-Agent' => $this->getConfig()->getUserAgent(),
        ]);

        if (count($headers) > 0) {
            $plugins[] = new HeaderSetPlugin($headers);
        }

        $plugins[] = new ContentLengthPlugin();
        $plugins[] = new LoggerPlugin($this->logger);
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
     * @inheritdoc
     */
    protected function createPluginClient(HttpClient $client, array $plugins = []) : PluginClient
    {
        return new PluginClient($client, $plugins);
    }

    /**
     * @inheritdoc
     */
    protected function createAccountClient(
        AccountInterface $account,
        array $options = []
    ) : GuzzleAdapter {
        $options['http_errors']     = false;
        $options['allow_redirects'] = true;

        if ($account->getProxy() !== null) {
            $options['proxy'] = $account->getProxy()->getProxyProtocol();
        }

        $options['cookies'] = $this->cookieJarBuilder->createCookieJar($account);

        $stack = HandlerStack::create(new CurlHandler());
        $stack->push(Middleware::retry($this->createRetryHandler()));

        $options['stack']   = $stack;
        $options['timeout'] = 5;

        $guzzle = new Client($options);

        return new GuzzleAdapter($guzzle);
    }

    /**
     * @inheritdoc
     */
    protected function createRequest(
        string $method,
        string $uri,
        ?string $body = null,
        array $headers = []
    ) : RequestInterface {
        $url     = $this->urlFactory->createUri($uri);
        $request = $this->requestFactory->createRequest($method, $url);

        if ($body !== null) {
            $stream  = $this->streamFactory->createStream($body);
            $request = $request->withBody($stream);
        }

        if (count($headers) > 0) {
            foreach ($headers as $name => $header) {
                $request = $request->withHeader($name, $header);
            }
        }

        return $request;
    }

    protected function getConfig() : ConfigInterface
    {
        return $this->config;
    }

    protected function createRetryHandler() : Closure
    {
        return static function (
            $retries,
            Psr7Request $request,
            ?Psr7Response $response = null,
            ?RequestException $exception = null
        ) {
            return $retries < self::MAX_RETRIES;
        };
    }

    protected function isServerError(?Psr7Response $response = null) : bool
    {
        return $response !== null && $response->getStatusCode() >= 500;
    }

    protected function isConnectError(?RequestException $exception = null) : bool
    {
        return $exception instanceof ConnectException;
    }
}
