<?php

namespace Shapecode\FUT\Authentication;

use Shapecode\FUT\Model\ProxyInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Cookie\FileCookieJar;

/**
 * Class Account
 *
 * @package Shapecode\FUT\Authentication
 * @author  Shapecode
 */
class Account implements AccountInterface
{

    /** @var CredentialsInterface */
    protected $credentials;

    /** @var Session */
    protected $session;

    /** @var CookieJarInterface */
    protected $cookieJar;

    /** @var ProxyInterface|null */
    protected $proxy;

    /** @var ClientInterface */
    protected $client;

    /**
     * @param CredentialsInterface    $credentials
     * @param SessionInterface|null   $session
     * @param ProxyInterface|null     $proxy
     * @param CookieJarInterface|null $cookieJar
     */
    public function __construct(CredentialsInterface $credentials, SessionInterface $session = null, ProxyInterface $proxy = null, CookieJarInterface $cookieJar = null)
    {
        $this->credentials = $credentials;
        $this->proxy = $proxy;
        $this->cookieJar = $cookieJar;
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @inheritdoc
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @inheritdoc
     */
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function resetSession()
    {
        $this->session = null;
    }

    /**
     * @inheritdoc
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @inheritdoc
     */
    public function getCookieJar()
    {
        if ($this->cookieJar === null) {
            $this->cookieJar = $this->createFileCookieJarByTemp();
        }

        return $this->cookieJar;
    }

    /**
     * @inheritdoc
     */
    public function setCookieJar(CookieJarInterface $cookieJar)
    {
        $this->cookieJar = $cookieJar;
    }

    /**
     *
     */
    public function createFileCookieJarByTemp()
    {
        $filename = sys_get_temp_dir() . '/' . sha1($this->getCredentials()->getEmail());
        $this->createFileCookieJarByFilename($filename);
    }

    /**
     * @param $filename
     */
    public function createFileCookieJarByFilename($filename)
    {
        $cookieJar = new FileCookieJar($filename, true);
        $this->setCookieJar($cookieJar);
    }
}
