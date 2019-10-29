<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

use GuzzleHttp\Cookie\CookieJarInterface;
use Shapecode\FUT\Client\Model\ProxyInterface;

/**
 * Interface AccountInterface
 */
interface AccountInterface
{
    public function getCredentials() : CredentialsInterface;

    public function getSession() : SessionInterface;

    public function setSession(SessionInterface $session) : void;

    public function resetSession() : void;

    public function getProxy() : ?ProxyInterface;

    public function getCookieJar() : CookieJarInterface;

    public function setCookieJar(CookieJarInterface $cookieJar) : void;
}
