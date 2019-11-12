<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Authentication;

use Shapecode\FUT\Client\Exception\FutException;
use Webmozart\Assert\Assert;
use function in_array;

class Credentials implements CredentialsInterface
{
    /** @var string */
    protected $email;

    /** @var string */
    protected $password;

    /** @var string */
    protected $platform;

    /** @var string */
    protected $emulate = 'web';

    /** @var string */
    protected $locale = 'en_US';

    /** @var string */
    protected $country = 'US';

    public function __construct(
        string $email,
        string $password,
        string $platform,
        string $locale = 'en_US',
        string $country = 'US',
        string $emulate = 'web'
    ) {
        Assert::email($email);
        Assert::notEmpty($password);
        Assert::notEmpty($locale);
        Assert::notEmpty($country);
        Assert::notEmpty($emulate);

        $this->email    = $email;
        $this->password = $password;
        $this->locale   = $locale;
        $this->country  = $country;
        $this->emulate  = $emulate;

        $this->setPlatform($platform);
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getPlatform() : string
    {
        return $this->platform;
    }

    public function getEmulate() : string
    {
        return $this->emulate;
    }

    public function getLocale() : string
    {
        return $this->locale;
    }

    public function getCountry() : string
    {
        return $this->country;
    }

    protected function setPlatform(string $platform) : void
    {
        if (! in_array($platform, self::VALID_PLATFORMS, true)) {
            throw new FutException('Wrong platform. (Valid ones are pc/xbox/xbox360/ps3/ps4)', [
                'reason' => 'invalid_platform',
            ]);
        }

        $this->platform = $platform;
    }
}
