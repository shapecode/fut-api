<?php

namespace Shapecode\FUT\Model;

/**
 * Class Proxy
 *
 * @package Shapecode\FUT\Model
 * @author  Shapecode
 */
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

    /**
     * @param string      $protocol
     * @param string      $ip
     * @param string      $port
     * @param null|string $username
     * @param null|string $password
     */
    public function __construct($protocol, $ip, $port, $username = null, $password = null)
    {
        $this->protocol = $protocol;
        $this->ip = $ip;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return null|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param null|string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getProxyProtocol()
    {
        $auth = '';

        if ($this->getUsername()) {
            $auth = $this->getUsername();

            if ($this->getPassword()) {
                $auth .= ':' . $this->getPassword();
            }

            $auth .= '@';
        }

        return $this->getProtocol() . '://' . $auth . $this->getIp() . ':' . $this->getPort();
    }
}
