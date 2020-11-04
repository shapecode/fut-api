<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

interface AccountInterface
{
    public function getCredentials(): CredentialsInterface;

    public function getSession(): ?SessionInterface;

    public function setSession(SessionInterface $session): void;

    public function resetSession(): void;
}
