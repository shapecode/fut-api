<?php

namespace Shapecode\FUT\Client\Authentication;

use Shapecode\FUT\Client\Model\ProxyInterface;
use GuzzleHttp\Cookie\CookieJarInterface;

/**
 * Interface AccountInterface
 *
 * @package Shapecode\FUT\Client\Authentication
 * @author  Shapecode
 */
interface AccountInterface
{

    /**
     * @return CredentialsInterface
     */
    public function getCredentials();

    /**
     * @return SessionInterface
     */
    public function getSession();

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session);

    /**
     *
     */
    public function resetSession();

    /**
     * @return ProxyInterface|null
     */
    public function getProxy();

    /**
     * @return CookieJarInterface
     */
    public function getCookieJar();

    /**
     * @param CookieJarInterface $cookieJar
     */
    public function setCookieJar(CookieJarInterface $cookieJar);
}
