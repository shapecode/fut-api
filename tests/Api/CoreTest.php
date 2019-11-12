<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Tests\Api;

use Shapecode\FUT\Client\Api\Core;
use Shapecode\FUT\Client\Authentication\Account;
use Shapecode\FUT\Client\Authentication\Credentials;
use Shapecode\FUT\Client\Exception\ProvideSecurityCodeException;
use Shapecode\FUT\Client\Tests\TestCase;
use function getenv;

class CoreTest extends TestCase
{
    public function testLogin() : void
    {
        $credentials = new Credentials(
            getenv('EMAIL'),
            getenv('PASSWORD'),
            getenv('PLATFORM'),
        );

        $account = new Account($credentials);

        $core = new Core($account);

        $this->expectException(ProvideSecurityCodeException::class);
        $this->expectExceptionMessage('You must provide a backup code');

        $core->login();
    }
}
