<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Shapecode\FUT\Client\Api\CoreInterface;
use Shapecode\FUT\Client\Authentication\AccountInterface;
use Shapecode\FUT\Client\Config\ConfigInterface;

use function array_merge;
use function count;
use function http_build_query;

class ClientFactory implements ClientFactoryInterface
{
    protected ClientInterface $client;

    protected ConfigInterface $config;

    protected RequestFactoryInterface $requestFactory;

    protected StreamFactoryInterface $streamFactory;

    protected UriFactoryInterface $urlFactory;

    public function __construct(
        ConfigInterface $config,
        ?ClientInterface $client = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        ?UriFactoryInterface $urlFactory = null
    ) {
        $this->config         = $config;
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory  = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->urlFactory     = $urlFactory ?? Psr17FactoryDiscovery::findUriFactory();
        $this->client         = $client ?? Psr18ClientDiscovery::find();
    }

    /**
     * @inheritdoc
     */
    public function request(
        AccountInterface $account,
        string $method,
        string $url,
        array $params,
        string $body,
        array $headers = []
    ): ResponseInterface {
        $headers = array_merge(
            CoreInterface::REQUEST_HEADERS,
            [
                'User-Agent' => $this->config->getUserAgent(),
            ],
            $headers
        );

        $request = $this->createRequest($method, $url, $params, $body, $headers);

        return $this->client->sendRequest($request);
    }

    protected function createRequest(
        string $method,
        string $url,
        array $params,
        ?string $body = null,
        array $headers = []
    ): RequestInterface {
        $uri = $this->urlFactory->createUri($url);

        if (count($params) > 0) {
            $query = http_build_query($params);
            $uri   = $uri->withQuery($query);
        }

        $request = $this->requestFactory->createRequest($method, $uri);

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
}
