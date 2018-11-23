<?php

namespace Shapecode\FUT\Client\Authentication;

/**
 * Class Session
 *
 * @package Shapecode\FUT\Client\Authentication
 * @author  Shapecode
 */
class Session implements SessionInterface
{

    /** @var string */
    protected $persona;

    /** @var string */
    protected $nucleus;

    /** @var string */
    protected $phishing;

    /** @var string */
    protected $session;

    /** @var string */
    protected $dob;

    /** @var string */
    protected $accessToken;

    /** @var string */
    protected $tokenType;

    /** @var \DateTime|null */
    protected $expiresAt;

    /**
     * @param string         $persona
     * @param string         $nucleus
     * @param string         $phishing
     * @param string         $session
     * @param string         $dob
     * @param string         $accessToken
     * @param string         $tokenType
     * @param \DateTime|null $tokenType
     */
    public function __construct($persona, $nucleus, $phishing, $session, $dob, $accessToken, $tokenType, \DateTime $expiresAt = null)
    {
        $this->persona = $persona;
        $this->nucleus = $nucleus;
        $this->phishing = $phishing;
        $this->session = $session;
        $this->dob = $dob;
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->expiresAt = $expiresAt;
    }

    /**
     * @param           $persona
     * @param           $nucleus
     * @param           $phishing
     * @param           $session
     * @param           $dob
     * @param           $accessToken
     * @param           $tokenType
     * @param \DateTime $expiresAt
     *
     * @return Session
     */
    public static function create($persona, $nucleus, $phishing, $session, $dob, $accessToken, $tokenType, \DateTime $expiresAt)
    {
        return new Session($persona, $nucleus, $phishing, $session, $dob, $accessToken, $tokenType, $expiresAt);
    }

    /**
     * @return string
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * @return string
     */
    public function getNucleus()
    {
        return $this->nucleus;
    }

    /**
     * @return string
     */
    public function getPhishing()
    {
        return $this->phishing;
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }
}
