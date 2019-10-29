<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http;

use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Shapecode\FUT\Client\Authentication\AccountInterface;

/**
 * Interface ClientFactoryInterface
 */
interface ClientFactoryInterface
{
    /**
     * @param mixed[] $options
     */
    public function createPluginClient(HttpClient $client, array $options = []) : PluginClient;

    /**
     * @param mixed[] $headers
     */
    public function createRequest(string $method, string $uri, ?string $body = null, array $headers = []) : RequestInterface;

    /**
     * @param mixed[] $options
     * @param mixed[] $plugins
     */
    public function request(AccountInterface $account, string $method, string $url, array $options = [], array $plugins = []) : ClientCall;
}
