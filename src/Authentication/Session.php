<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

use DateTime;

class Session implements SessionInterface
{
    protected string $persona;

    protected string $nucleus;

    protected string $phishing;

    protected string $session;

    protected string $dob;

    protected string $accessToken;

    protected ?string $tokenType = null;

    protected ?DateTime $expiresAt = null;

    public function __construct(
        string $persona,
        string $nucleus,
        string $phishing,
        string $session,
        string $dob,
        string $accessToken,
        ?string $tokenType,
        ?DateTime $expiresAt = null
    ) {
        $this->persona     = $persona;
        $this->nucleus     = $nucleus;
        $this->phishing    = $phishing;
        $this->session     = $session;
        $this->dob         = $dob;
        $this->accessToken = $accessToken;
        $this->tokenType   = $tokenType;
        $this->expiresAt   = $expiresAt;
    }

    public static function create(
        string $persona,
        string $nucleus,
        string $phishing,
        string $session,
        string $dob,
        string $accessToken,
        ?string $tokenType = null,
        ?DateTime $expiresAt = null
    ): Session {
        return new self($persona, $nucleus, $phishing, $session, $dob, $accessToken, $tokenType, $expiresAt);
    }

    public function getPersona(): string
    {
        return $this->persona;
    }

    public function getNucleus(): string
    {
        return $this->nucleus;
    }

    public function getPhishing(): string
    {
        return $this->phishing;
    }

    public function getSession(): string
    {
        return $this->session;
    }

    public function getDob(): string
    {
        return $this->dob;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    public function getExpiresAt(): ?DateTime
    {
        return $this->expiresAt;
    }
}
