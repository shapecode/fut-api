<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

use GuzzleHttp\ClientInterface;
use RuntimeException;
use Shapecode\FUT\Client\Model\ProxyInterface;

class Account implements AccountInterface
{
    /** @var CredentialsInterface */
    protected $credentials;

    /** @var SessionInterface|null */
    protected $session;

    /** @var ProxyInterface|null */
    protected $proxy;

    /** @var ClientInterface */
    protected $client;

    public function __construct(
        CredentialsInterface $credentials,
        ?SessionInterface $session = null,
        ?ProxyInterface $proxy = null
    ) {
        $this->credentials = $credentials;
        $this->proxy       = $proxy;
        $this->session     = $session;
    }

    public function getCredentials() : CredentialsInterface
    {
        return $this->credentials;
    }

    public function getSession() : SessionInterface
    {
        if ($this->session === null) {
            throw new RuntimeException('session has to be set');
        }

        return $this->session;
    }

    public function setSession(SessionInterface $session) : void
    {
        $this->session = $session;
    }

    public function resetSession() : void
    {
        $this->session = null;
    }

    public function getProxy() : ?ProxyInterface
    {
        return $this->proxy;
    }
}
