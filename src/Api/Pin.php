<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Api;

use Shapecode\FUT\Client\Authentication\AccountInterface;
use Shapecode\FUT\Client\Exception\FutResponseException;
use Shapecode\FUT\Client\Http\ClientFactoryInterface;
use const JSON_THROW_ON_ERROR;
use function date;
use function json_decode;
use function json_encode;
use function substr;

class Pin implements PinInterface
{
    /** @var AccountInterface */
    protected $account;

    /** @var ClientFactoryInterface */
    protected $clientFactory;

    /** @var mixed */
    protected $gid;

    /** @var mixed */
    protected $et;

    /** @var mixed */
    protected $pidt;

    /** @var mixed */
    protected $v;

    /** @var mixed */
    protected $s;

    public function __construct(AccountInterface $account, ClientFactoryInterface $clientFactory)
    {
        $this->account       = $account;
        $this->clientFactory = $clientFactory;
    }

    /**
     * @inheritdoc
     */
    public function sendEvent($en, bool $pgid = false, bool $status = false, bool $source = false, bool $end_reason = false) : void
    {
        $event = $this->event($en, $pgid, $status, $source, $end_reason);
        $this->send([$event]);
    }

    /**
     * @inheritdoc
     */
    public function event($en, bool $pgid = false, bool $status = false, bool $source = false, bool $end_reason = false) : array
    {
        $account = $this->account;
        $session = $account->getSession();

        $data                = [
            'core' => [
                's'        => $this->s,
                'pidt'     => $this->pidt,
                'pid'      => $session->getPersona(),
                'pidm'     => [
                    'nucleus' => $session->getNucleus(),
                ],
                'didm'     => [
                    'uuid' => '0',
                ],
                'ts_event' => $this->ts(),
                'en'       => $en,
            ],
        ];
        $data['core']['dob'] = $session->getDob();
        if ($pgid) {
            $data['pgid'] = $pgid;
        }
        if ($status) {
            $data['status'] = $status;
        }
        if ($source) {
            $data['source'] = $source;
        }
        if ($end_reason) {
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
     * @inheritDoc
     */
    public function send(array $events) : bool
    {
        $account  = $this->account;
        $session  = $account->getSession();
        $platform = $account->getCredentials()->getPlatform();

        $body    = json_encode([
//            'taxv'    => $this->taxv,
//            'tidt'    => $this->tidt,
//            'tid'     => $this->sku,
//            'rel'     => $this->rel,
//            'v'       => $this->v,
//            'gid'     => $this->gid,
//            'plat'    => $this->plat,
//            'et'      => $this->et,
            'taxv'    => '1.1',
            'tidt'    => 'easku',
            'tid'     => 'FUT20WEB',
            'rel'     => 'prod',
            'v'       => '20.0.0',
            'gid'     => 0,
            'plat'    => 'web',
            'et'      => 'client',
            'ts_post' => $this->ts(),
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

        $call     = $this->clientFactory->request($account, 'POST', self::PIN_URL, [
            'body'    => $body,
            'headers' => $headers,
        ]);
        $response = $call->getResponse();

        $content = json_decode($response->getBody()->getContents(), true);

        if ($content['status'] !== 'ok') {
            throw new FutResponseException('PinEvent is NOT OK, probably they changed something.', $response, 'pin_event');
        }

        return true;
    }

    private function ts() : string
    {
        return date('Y-m-dTH:i:s') . '.' . date('v') . 'Z';
    }
}
