<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

use DateTime;

interface SessionInterface
{
    public function getPersona() : string;

    public function getNucleus() : string;

    public function getPhishing() : string;

    public function getSession() : string;

    public function getDob() : string;

    public function getAccessToken() : string;

    public function getTokenType() : ?string;

    public function getExpiresAt() : ?DateTime;
}
