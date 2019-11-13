<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Api;

use Carbon\Carbon;
use RuntimeException;
use Shapecode\FUT\Client\Authentication\AccountInterface;
use Shapecode\FUT\Client\Exception\PinErrorException;
use Shapecode\FUT\Client\Http\ClientFactoryInterface;
use const JSON_THROW_ON_ERROR;
use function json_decode;
use function json_encode;
use function mb_strlen;
use function substr;

class Pin
{
    private const PIN_URL         = 'https://pin-river.data.ea.com/pinEvents';
    private const DATETIME_FORMAT = 'Y-m-d\TH:i:s.v\Z';

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

        if ($session === null) {
            throw new RuntimeException('session has to be set');
        }

        $data = [
            'core' => [
                'dob'      => $session->getDob(),
                'en'       => $en,
                'pid'      => $session->getPersona(),
                'pidm'     => [
                    'nucleus' => $session->getNucleus(),
                ],
                'pidt'     => 'persona',
                's'        => $this->s,
//                'didm'     => [
//                    'uuid' => '0',
//                ],
                'ts_event' => $this->timestamp(),
            ],
        ];

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

        switch ($en) {
            case 'login':
                $data['type']   = 'utas';
                $data['userid'] = $session->getPersona();
                break;
            case 'page_view':
                $data['type'] = 'menu';
                break;
            case 'error':
                $data['server_type'] = 'utas';
                $data['errid']       = 'server_error';
                $data['type']        = 'disconnect';
                $data['sid']         = $session->getSession();
                break;
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

        if ($session === null) {
            throw new RuntimeException('session has to be set');
        }

        $body = json_encode([
            'custom'  => [
                'networkAccess' => 'G',
                'service_plat'  => substr($platform, 0, 3),
            ],
            'et'      => 'client',
            'events'  => $events,
            'gid'     => 0,
            'is_sess' => true,
            'loc'     => 'en_US',
            'plat'    => 'web',
            'rel'     => 'prod',
            'sid'     => $session->getSession(),
            'taxv'    => '1.1',
            'tid'     => 'FUT20WEB',
            'tidt'    => 'easku',
            'ts_post' => $this->timestamp(),
            'v'       => '20.1.0',
        ], JSON_THROW_ON_ERROR);

        $headers = [
            'Accept'            => '*/*',
            'Accept-Encoding'   => 'gzip, deflate, br',
            'Accept-Language'   => 'en-US,de;q=0.9,en-US;q=0.8,en;q=0.7,lb;q=0.6',
            'Cache-Control'     => 'no-cache',
            'Connection'        => 'kkeep-alive',
            'Content-Length'    => mb_strlen($body),
            'Content-Type'      => 'application/json',
            'DNT'               => '1',
            'Host'              => 'pin-river.data.ea.com',
            'Origin'            => 'https://www.easports.com',
            'Pragma'            => 'no-cache',
            'Referer'           => 'https://www.easports.com/fifa/ultimate-team/web-app/',
            'Sec-Fetch-Mode'    => 'cors',
            'Sec-Fetch-Site'    => 'cross-site',
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
        return Carbon::now()->format(self::DATETIME_FORMAT);
    }
}
