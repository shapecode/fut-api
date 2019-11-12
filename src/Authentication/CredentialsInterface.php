<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

interface CredentialsInterface
{
    public const PLATFORM_PC       = 'pc';
    public const PLATFORM_XBOX     = 'xbox';
    public const PLATFORM_XBOX_360 = 'xbox360';
    public const PLATFORM_PS3      = 'ps3';
    public const PLATFORM_PS4      = 'ps4';

    public const VALID_PLATFORMS = [
        self::PLATFORM_PC,
        self::PLATFORM_XBOX,
        self::PLATFORM_XBOX_360,
        self::PLATFORM_PS3,
        self::PLATFORM_PS4,
    ];

    public function getEmail() : string;

    public function getPassword() : string;

    public function getPlatform() : string;

    public function getEmulate() : string;

    public function getLocale() : string;

    public function getCountry() : string;
}
