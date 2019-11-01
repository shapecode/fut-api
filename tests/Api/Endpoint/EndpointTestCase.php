<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Tests\Api\Endpoint;

use Carbon\Carbon;
use PHPStan\Testing\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use ReflectionClass;
use Shapecode\FUT\Client\Api\Core;
use Shapecode\FUT\Client\Api\Pin;
use Shapecode\FUT\Client\Authentication\Account;
use Shapecode\FUT\Client\Authentication\Credentials;
use Shapecode\FUT\Client\Authentication\Session;
use Shapecode\FUT\Client\Config\Config;
use Shapecode\FUT\Client\Http\ClientCall;
use Shapecode\FUT\Client\Http\ClientFactory;
use function file_get_contents;

abstract class EndpointTestCase extends TestCase
{
    protected function createCore(ClientFactory $factory) : Core
    {
        $date = Carbon::now('UTC');
        Carbon::setTestNow($date);

        $session = $this->createMock(Session::class);
        $session->method('getDob')->willReturn('2019-02-15');
        $session->method('getPersona')->willReturn('persona_stuff');
        $session->method('getNucleus')->willReturn('nucleus_stuff');
        $session->method('getSession')->willReturn('session_stuff');

        $credentials = $this->createMock(Credentials::class);
        $credentials->method('getPlatform')->willReturn('ps4');

        $account = $this->createMock(Account::class);
        $account->method('getSession')->willReturn($session);
        $account->method('getCredentials')->willReturn($credentials);

        $pin = $this->createMock(Pin::class);

        $config = new Config();

        $core = new Core($account, $config, $factory);

        $reflectionClass = new ReflectionClass($core);

        $reflectionProperty = $reflectionClass->getProperty('pin');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($core, $pin);

        return $core;
    }

    protected function createClientFactoryMock(string $filename) : ClientFactory
    {
        $jsonData = file_get_contents(__DIR__ . '/../../../data/fixtures/' . $filename);

        $body = $this->createMock(StreamInterface::class);
        $body->method('getContents')->willReturn($jsonData);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('getStatusCode')->willReturn(200);

        $clientCall = $this->createMock(ClientCall::class);
        $clientCall->method('getResponse')->willReturn($response);
        $clientCall->method('getContent')->willReturn($jsonData);

        $factory = $this->createMock(ClientFactory::class);
        $factory->method('request')->willReturn($clientCall);

        return $factory;
    }
}
