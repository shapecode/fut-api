<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Tests\Authentication;

use InvalidArgumentException;
use Shapecode\FUT\Client\Authentication\Credentials;
use Shapecode\FUT\Client\Authentication\CredentialsInterface;
use Shapecode\FUT\Client\Exception\FutException;
use Shapecode\FUT\Client\Tests\TestCase;

class CredentialsTest extends TestCase
{
    public function testCredentialCreation() : void
    {
        new Credentials(
            'test@example.com',
            'password',
            CredentialsInterface::PLATFORM_PS4
        );

        $this->expectNotToPerformAssertions();
    }

    public function testCredentialFailure() : void
    {
        $this->expectException(FutException::class);
        $this->expectExceptionMessage('Wrong platform. (Valid ones are pc/xbox/xbox360/ps3/ps4)');

        new Credentials(
            'test@example.com',
            'password',
            'wrong_platform'
        );
    }

    public function testCredentialEmail() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value to be a valid e-mail address. Got "wrong_email"');

        new Credentials(
            'wrong_email',
            'password',
            CredentialsInterface::PLATFORM_PS4
        );
    }

    public function testCredentialPassword() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-empty value. Got: ""');

        new Credentials(
            'test@example.com',
            '',
            CredentialsInterface::PLATFORM_PS4
        );
    }
}
