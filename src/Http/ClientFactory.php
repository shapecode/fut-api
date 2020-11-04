<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as Psr7Response;
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

use function array_merge;
use function count;

class ClientFactory implements ClientFactoryInterface
{
    protected RequestFactoryInterface $requestFactory;

    protected StreamFactoryInterface $streamFactory;

    protected UriFactoryInterface $urlFactory;

    protected ConfigInterface $config;

    protected LoggerInterface $logger;

    public const MAX_RETRIES = 4;

    public function __construct(
        ConfigInterface $config,
        ?LoggerInterface $logger = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        ?UriFactoryInterface $urlFactory = null
    ) {
        $this->config = $config;
        $this->logger = $logger ?? new NullLogger();

        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory  = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->urlFactory     = $urlFactory ?? Psr17FactoryDiscovery::findUriFactory();
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
    ): ClientCall {
        $headers = [];

        if (isset($options['headers'])) {
            /** @var mixed[] $headers */
            $headers = $options['headers'];
            unset($options['headers']);
        }

        $call = new ClientCall();

        $headers = array_merge(
            $headers,
            CoreInterface::REQUEST_HEADERS,
            [
                'User-Agent' => $this->getConfig()->getUserAgent(),
            ]
        );

        $plugins[] = new ClientCallPlugin($call);

        $guzzle = $this->createAccountClient($options);
        $client = $this->createPluginClient($guzzle, $plugins);

        $request = $this->createRequest($method, $url, null, $headers);

        $client->sendRequest($request);

        return $call;
    }

    /**
     * @inheritdoc
     */
    protected function createPluginClient(HttpClient $client, array $plugins = []): PluginClient
    {
        return new PluginClient($client, $plugins);
    }

    /**
     * @inheritdoc
     */
    protected function createAccountClient(
        array $options = []
    ): GuzzleAdapter {
        $options['http_errors']     = false;
        $options['allow_redirects'] = true;
        $options['timeout']         = 5;

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
    ): RequestInterface {
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

    protected function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    protected function isServerError(?Psr7Response $response = null): bool
    {
        return $response !== null && $response->getStatusCode() >= 500;
    }

    protected function isConnectError(?RequestException $exception = null): bool
    {
        return $exception instanceof ConnectException;
    }
}
