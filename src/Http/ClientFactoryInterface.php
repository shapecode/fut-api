<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http;

use Shapecode\FUT\Client\Authentication\AccountInterface;

interface ClientFactoryInterface
{
    /**
     * @param mixed[] $options
     * @param mixed[] $plugins
     */
    public function request(
        AccountInterface $account,
        string $method,
        string $url,
        array $options = [],
        array $plugins = []
    ) : ClientCall;
}
