<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

use Shapecode\FUT\Client\Model\ProxyInterface;

interface AccountInterface
{
    public function getCredentials() : CredentialsInterface;

    public function getSession() : ?SessionInterface;

    public function setSession(SessionInterface $session) : void;

    public function resetSession() : void;

    public function getProxy() : ?ProxyInterface;
}
