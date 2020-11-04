<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

class Account implements AccountInterface
{
    protected CredentialsInterface $credentials;

    protected ?SessionInterface $session = null;

    public function __construct(
        CredentialsInterface $credentials,
        ?SessionInterface $session = null
    ) {
        $this->credentials = $credentials;
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
}
