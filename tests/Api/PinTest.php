<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Tests\Api;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Shapecode\FUT\Client\Api\Pin;
use Shapecode\FUT\Client\Authentication\Account;
use Shapecode\FUT\Client\Authentication\Session;
use Shapecode\FUT\Client\Http\ClientFactory;

class PinTest extends TestCase
{
    public function testCreateEvent() : void
    {
        $date = Carbon::create(2001, 5, 21, 12, 32, 15, 'UTC');
        Carbon::setTestNow($date);

        $session = $this->createMock(Session::class);
        $session->method('getDob')->willReturn('2019-02-15');
        $session->method('getPersona')->willReturn('persona_stuff');
        $session->method('getNucleus')->willReturn('nucleus_stuff');
        $session->method('getSession')->willReturn('session_stuff');

        $account = $this->createMock(Account::class);
        $account->method('getSession')->willReturn($session);

        $factory = $this->createMock(ClientFactory::class);

        $pin = new Pin($account, $factory);

        $eventLogin = $pin->event('login', 'Page');
        $eventError = $pin->event('error');

        self::assertEquals([
            'pgid'   => 'Page',
            'type'   => 'utas',
            'userid' => 'persona_stuff',
            'core'   => [
                'dob'      => '2019-02-15',
                'en'       => 'login',
                'pid'      => 'persona_stuff',
                'pidm'     => [
                    'nucleus' => 'nucleus_stuff',
                ],
                'pidt'     => 'persona',
                's'        => 2,
                'ts_event' => $date->format('Y-m-d\TH:i:s.v\Z'),
            ],
        ], $eventLogin);

        self::assertEquals([
            'type'        => 'disconnect',
            'core'        => [
                'dob'      => '2019-02-15',
                'en'       => 'error',
                'pid'      => 'persona_stuff',
                'pidm'     => [
                    'nucleus' => 'nucleus_stuff',
                ],
                'pidt'     => 'persona',
                's'        => 3,
                'ts_event' => $date->format('Y-m-d\TH:i:s.v\Z'),
            ],
            'server_type' => 'utas',
            'errid'       => 'server_error',
            'sid'         => 'session_stuff',
        ], $eventError);
    }
}
