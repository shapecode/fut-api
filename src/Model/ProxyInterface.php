<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Model;

/**
 * Interface ProxyInterface
 */
interface ProxyInterface
{
    public function getProtocol() : string;

    public function getIp() : string;

    public function getPort() : string;

    public function getUsername() : ?string;

    public function getPassword() : ?string;

    public function getProxyProtocol() : string;
}
