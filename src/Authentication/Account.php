<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

use GuzzleHttp\ClientInterface;
use Shapecode\FUT\Client\Model\ProxyInterface;

class Account implements AccountInterface
{
    protected CredentialsInterface $credentials;

    protected ?SessionInterface $session = null;

    protected ?ProxyInterface $proxy = null;

    protected ClientInterface $client;

    public function __construct(
        CredentialsInterface $credentials,
        ?SessionInterface $session = null,
        ?ProxyInterface $proxy = null
    ) {
        $this->credentials = $credentials;
        $this->proxy       = $proxy;
        $this->session     = $session;
    }

    public function getCredentials(): CredentialsInterface
    {
        return $this->credentials;
    }

    public function getSession(): ?SessionInterface
    {
        return $this->session;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function resetSession(): void
    {
        $this->session = null;
    }

    public function getProxy(): ?ProxyInterface
    {
        return $this->proxy;
    }
}
