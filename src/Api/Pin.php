<?php

namespace Shapecode\FUT\Client\Api;

use Shapecode\FUT\Client\Authentication\AccountInterface;
use Shapecode\FUT\Client\Exception\FutResponseException;
use Shapecode\FUT\Client\Http\ClientFactoryInterface;

/**
 * Class Pin
 *
 * @package Shapecode\FUT\Client\Api
 * @author  Shapecode
 */
class Pin implements PinInterface
{

    /** @var AccountInterface */
    protected $account;

    /** @var ClientFactoryInterface */
    protected $clientFactory;

    /** @var mixed */
    protected $taxv, $tidt, $sku, $rel, $gid, $plat, $et, $pidt, $v, $s;

    /**
     * @param AccountInterface       $account
     * @param ClientFactoryInterface $clientFactory
     */
    public function __construct(AccountInterface $account, ClientFactoryInterface $clientFactory)
    {
        $this->account = $account;
        $this->clientFactory = $clientFactory;

        //pinvars
        $response = file_get_contents('https://www.easports.com/fifa/ultimate-team/web-app/js/compiled_1.js');

        $this->taxv = $m[preg_match('/taxv:"(.+?)"/', $response, $m)];
        $this->tidt = $m[preg_match('/tidt:"(.+?)"/', $response, $m)];

        $this->sku = $m[preg_match('/enums.SKU.FUT="(.+?)"/', $response, $m)];
        $this->rel = 'prod'; //REWRITE?
        $this->gid = $m[preg_match('/gid:([0-9]+?)/', $response, $m)];
        $this->plat = 'web'; //REWRITE?
        $this->et = $m[preg_match('/et:"(.+?)"/', $response, $m)];
        $this->pidt = $m[preg_match('/pidt:"(.+?)"/', $response, $m)];
        $this->v = $m[preg_match('/APP_VERSION="(.+?)"/', $response, $m)];
        $this->s = 2;
    }

    /**
     * @inheritdoc
     */
    public function sendEvent($en, $pgid = false, $status = false, $source = false, $end_reason = false)
    {
        $event = $this->event($en, $pgid, $status, $source, $end_reason);
        $this->send([$event]);
    }

    /**
     * @inheritdoc
     */
    public function event($en, $pgid = false, $status = false, $source = false, $end_reason = false)
    {
        $account = $this->account;
        $session = $account->getSession();

        $data = [
            'core' => [
                's'        => $this->s,
                'pidt'     => $this->pidt,
                'pid'      => $session->getPersona(),
                'pidm'     => [
                    'nucleus' => $session->getNucleus()
                ],
                'didm'     => [
                    'uuid' => '0'
                ],
                'ts_event' => $this->ts(),
                'en'       => $en
            ]
        ];
        if ($session->getDob() !== null) {
            $data['core']['dob'] = $session->getDob();
        }
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
            $data['type'] = "utas";
            $data['userid'] = $session->getPersona();
        } elseif ($en === 'page_view') {
            $data['type'] = "menu";
        } elseif ($en === 'error') {
            $data['server_type'] = 'utas';
            $data['errid'] = 'server_error';
            $data['type'] = 'disconnect';
            $data['sid'] = $session->getSession();
        }

        $this->s++;

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function send($events)
    {
        $account = $this->account;
        $session = $account->getSession();
        $platform = $account->getCredentials()->getPlatform();

        $body = json_encode([
            'taxv'    => $this->taxv,
            'tidt'    => $this->tidt,
            'tid'     => $this->sku,
            'rel'     => $this->rel,
            'v'       => $this->v,
            'ts_post' => $this->ts(),
            'sid'     => $session->getSession(),
            'gid'     => $this->gid,
            'plat'    => $this->plat,
            'et'      => $this->et,
            'loc'     => $account->getCredentials()->getLocale(),
            'is_sess' => $session->getSession() !== null,
            'custom'  => [
                'networkAccess' => 'G',
                'service_plat'  => substr($platform, 0, 3)
            ],
            'events'  => $events
        ]);
        $headers = [
            'Origin'            => 'https://www.easports.com',
            'Referer'           => 'https://www.easports.com/fifa/ultimate-team/web-app/',
            'x-ea-game-id'      => $this->sku,
            'x-ea-game-id-type' => $this->tidt,
            'x-ea-taxv'         => $this->taxv
        ];

        $call = $this->clientFactory->request($account, 'POST', self::PIN_URL, [
            'body'    => $body,
            'headers' => $headers
        ]);
        $response = $call->getResponse();

        $content = json_decode($response->getBody()->getContents(), true);

        if ($content['status'] !== 'ok') {
            throw new FutResponseException('PinEvent is NOT OK, probably they changed something.', $response, 'pin_event');
        }

        return true;
    }

    /**
     * @return string
     */
    private function ts()
    {
        return date('Y-m-dTH:i:s') . '.' . date('v') . 'Z';
    }

}
