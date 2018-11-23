<?php

namespace Shapecode\FUT\Client\Authentication;

use Shapecode\FUT\Client\Exception\FutException;

/**
 * Class Credentials
 *
 * @package Shapecode\FUT\Client\Authentication
 * @author  Shapecode
 */
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

    /**
     * @param string $email
     * @param string $password
     * @param string $platform
     * @param string $locale
     * @param string $country
     * @param string $emulate
     */
    public function __construct($email, $password, $platform, $locale = 'en_US', $country = 'US', $emulate = 'web')
    {
        $this->email = $email;
        $this->password = $password;
        $this->locale = $locale;
        $this->country = $country;
        $this->emulate = $emulate;

        $this->setPlatform($platform);
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @inheritdoc
     */
    public function getEmulate()
    {
        return $this->emulate;
    }

    /**
     * @inheritdoc
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @inheritdoc
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param $platform
     */
    protected function setPlatform($platform)
    {
        if (!in_array($platform, self::VALID_PLATFORMS, true)) {
            throw new FutException('Wrong platform. (Valid ones are pc/xbox/xbox360/ps3/ps4)', 0, null, [
                'reason' => 'invalid_platform'
            ]);
        }

        $this->platform = $platform;
    }
}
