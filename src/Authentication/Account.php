<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Cookie\FileCookieJar;
use Shapecode\FUT\Client\Model\ProxyInterface;
use function sha1;
use function sys_get_temp_dir;

class Account implements AccountInterface
{
    /** @var CredentialsInterface */
    protected $credentials;

    /** @var SessionInterface|null */
    protected $session;

    /** @var CookieJarInterface|null */
    protected $cookieJar;

    /** @var ProxyInterface|null */
    protected $proxy;

    /** @var ClientInterface */
    protected $client;

    public function __construct(CredentialsInterface $credentials, ?SessionInterface $session = null, ?ProxyInterface $proxy = null, ?CookieJarInterface $cookieJar = null)
    {
        $this->credentials = $credentials;
        $this->proxy       = $proxy;
        $this->cookieJar   = $cookieJar;
        $this->session     = $session;
    }

    /**
     * @inheritdoc
     */
    public function getCredentials() : CredentialsInterface
    {
        return $this->credentials;
    }

    /**
     * @inheritdoc
     */
    public function getSession() : SessionInterface
    {
        if($this->session === null) {
            throw new \RuntimeException('session has to be set');
        }

        return $this->session;
    }

    /**
     * @inheritdoc
     */
    public function setSession(SessionInterface $session) : void
    {
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function resetSession() : void
    {
        $this->session = null;
    }

    /**
     * @inheritdoc
     */
    public function getProxy() : ?ProxyInterface
    {
        return $this->proxy;
    }

    /**
     * @inheritdoc
     */
    public function getCookieJar() : CookieJarInterface
    {
        if ($this->cookieJar === null) {
            $this->cookieJar = $this->createFileCookieJarByTemp();
        }

        return $this->cookieJar;
    }

    /**
     * @inheritdoc
     */
    public function setCookieJar(CookieJarInterface $cookieJar) : void
    {
        $this->cookieJar = $cookieJar;
    }

    public function createFileCookieJarByTemp() : CookieJarInterface
    {
        $filename = sys_get_temp_dir() . '/' . sha1($this->getCredentials()->getEmail());

        return $this->createFileCookieJarByFilename($filename);
    }

    public function createFileCookieJarByFilename(string $filename) : CookieJarInterface
    {
        return new FileCookieJar($filename, true);
    }
}
