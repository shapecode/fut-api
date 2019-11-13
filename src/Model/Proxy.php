<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Model;

class Proxy implements ProxyInterface
{
    /** @var string */
    protected $protocol;

    /** @var string */
    protected $ip;

    /** @var string */
    protected $port;

    /** @var string|null */
    protected $username;

    /** @var string|null */
    protected $password;

    public function __construct(
        string $protocol,
        string $ip,
        string $port,
        ?string $username = null,
        ?string $password = null
    ) {
        $this->protocol = $protocol;
        $this->ip       = $ip;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function getProtocol() : string
    {
        return $this->protocol;
    }

    public function getIp() : string
    {
        return $this->ip;
    }

    public function setIp(string $ip) : void
    {
        $this->ip = $ip;
    }

    public function getPort() : string
    {
        return $this->port;
    }

    public function setPort(string $port) : void
    {
        $this->port = $port;
    }

    public function getUsername() : ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username) : void
    {
        $this->username = $username;
    }

    public function getPassword() : ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password) : void
    {
        $this->password = $password;
    }

    public function getProxyProtocol() : string
    {
        $auth = '';

        if ($this->getUsername() !== null) {
            $auth = $this->getUsername();

            if ($this->getPassword() !== null) {
                $auth .= ':' . $this->getPassword();
            }

            $auth .= '@';
        }

        return $this->getProtocol() . '://' . $auth . $this->getIp() . ':' . $this->getPort();
    }
}
