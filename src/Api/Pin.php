<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Api;

use Shapecode\FUT\Client\Authentication\AccountInterface;
use Shapecode\FUT\Client\Exception\PinErrorException;
use Shapecode\FUT\Client\Http\ClientFactoryInterface;
use const JSON_THROW_ON_ERROR;
use function date;
use function json_decode;
use function json_encode;
use function substr;

final class Pin
{
    public const PIN_URL = 'https://pin-river.data.ea.com/pinEvents';

    /** @var AccountInterface */
    private $account;

    /** @var ClientFactoryInterface */
    private $clientFactory;

    /** @var int */
    private $s = 2;

    public function __construct(AccountInterface $account, ClientFactoryInterface $clientFactory)
    {
        $this->account       = $account;
        $this->clientFactory = $clientFactory;
    }

    /**
     * @inheritdoc
     */
    public function sendEvent(
        string $en,
        ?string $pgid = null,
        ?string $status = null,
        ?string $source = null,
        ?string $end_reason = null
    ) : void {
        $event = $this->event($en, $pgid, $status, $source, $end_reason);
        $this->send([$event]);
    }

    /**
     * @return mixed[]
     */
    public function event(
        string $en,
        ?string $pgid = null,
        ?string $status = null,
        ?string $source = null,
        ?string $end_reason = null
    ) : array {
        $account = $this->account;
        $session = $account->getSession();

        $data                = [
            'core' => [
                's'        => $this->s,
                'pidt'     => 'persona',
                'pid'      => $session->getPersona(),
                'pidm'     => [
                    'nucleus' => $session->getNucleus(),
                ],
                'didm'     => [
                    'uuid' => '0',
                ],
                'ts_event' => $this->timestamp(),
                'en'       => $en,
            ],
        ];
        $data['core']['dob'] = $session->getDob();
        if ($pgid !== null) {
            $data['pgid'] = $pgid;
        }
        if ($status !== null) {
            $data['status'] = $status;
        }
        if ($source !== null) {
            $data['source'] = $source;
        }
        if ($end_reason !== null) {
            $data['end_reason'] = $end_reason;
        }
        if ($en === 'login') {
            $data['type']   = 'utas';
            $data['userid'] = $session->getPersona();
        } elseif ($en === 'page_view') {
            $data['type'] = 'menu';
        } elseif ($en === 'error') {
            $data['server_type'] = 'utas';
            $data['errid']       = 'server_error';
            $data['type']        = 'disconnect';
            $data['sid']         = $session->getSession();
        }

        $this->s++;

        return $data;
    }

    /**
     * @param mixed[] $events
     */
    public function send(array $events) : bool
    {
        $account  = $this->account;
        $session  = $account->getSession();
        $platform = $account->getCredentials()->getPlatform();

        $body = json_encode([
            'taxv'    => '1.1',
            'tidt'    => 'easku',
            'tid'     => 'FUT20WEB',
            'rel'     => 'prod',
            'v'       => '20.0.0',
            'gid'     => 0,
            'plat'    => 'web',
            'et'      => 'client',
            'ts_post' => $this->timestamp(),
            'sid'     => $session->getSession(),
            'loc'     => $account->getCredentials()->getLocale(),
            'is_sess' => 1,
            'custom'  => [
                'networkAccess' => 'G',
                'service_plat'  => substr($platform, 0, 3),
            ],
            'events'  => $events,
        ], JSON_THROW_ON_ERROR);

        $headers = [
            'Origin'            => 'https://www.easports.com',
            'Referer'           => 'https://www.easports.com/fifa/ultimate-team/web-app/',
            'x-ea-game-id'      => 'FUT20WEB',
            'x-ea-game-id-type' => 'easku',
            'x-ea-taxv'         => '1.1',
        ];

        $call = $this->clientFactory->request($account, 'POST', self::PIN_URL, [
            'body'    => $body,
            'headers' => $headers,
        ]);

        $content = json_decode($call->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if ($content['status'] !== 'ok') {
            throw new PinErrorException($call->getResponse());
        }

        return true;
    }

    private function timestamp() : string
    {
        return date('Y-m-dTH:i:s') . '.' . date('v') . 'Z';
    }
}
