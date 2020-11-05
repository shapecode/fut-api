<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http;

use Psr\Http\Message\ResponseInterface;
use Shapecode\FUT\Client\Authentication\AccountInterface;

interface ClientFactoryInterface
{
    /**
     * @param mixed[] $params
     * @param mixed[] $headers
     */
    public function request(
        AccountInterface $account,
        string $method,
        string $body,
        array $params,
        string $url,
        array $headers = []
    ): ResponseInterface;
}
