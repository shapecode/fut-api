<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

/**
 * Interface CredentialsInterface
 */
interface CredentialsInterface
{
    public const VALID_PLATFORMS = [
        'pc',
        'xbox',
        'xbox360',
        'ps3',
        'ps4',
    ];

    public function getEmail() : string;

    public function getPassword() : string;

    public function getPlatform() : string;

    public function getEmulate() : string;

    public function getLocale() : string;

    public function getCountry() : string;
}
