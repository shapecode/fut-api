<?php

namespace Shapecode\FUT\Client\Api;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Shapecode\FUT\Client\Authentication\AccountInterface;
use Shapecode\FUT\Client\Authentication\Session;
use Shapecode\FUT\Client\Config\Config;
use Shapecode\FUT\Client\Config\ConfigInterface;
use Shapecode\FUT\Client\Exception\AuthFailedException;
use Shapecode\FUT\Client\Exception\CaptchaException;
use Shapecode\FUT\Client\Exception\FutResponseException;
use Shapecode\FUT\Client\Exception\IncorrectCredentialsException;
use Shapecode\FUT\Client\Exception\IncorrectSecurityCodeException;
use Shapecode\FUT\Client\Exception\MaxSessionsException;
use Shapecode\FUT\Client\Exception\NoPersonaException;
use Shapecode\FUT\Client\Exception\NoSessionException;
use Shapecode\FUT\Client\Exception\PermissionDeniedException;
use Shapecode\FUT\Client\Exception\ProvideSecurityCodeException;
use Shapecode\FUT\Client\Exception\ServerDownException;
use Shapecode\FUT\Client\Exception\SessionExpiredException;
use Shapecode\FUT\Client\Exception\TemporaryBanException;
use Shapecode\FUT\Client\Exception\ToManyRequestsException;
use Shapecode\FUT\Client\Exception\TransferMarketDisabledException;
use Shapecode\FUT\Client\Exception\UserExpiredException;
use Shapecode\FUT\Client\Http\ClientFactory;
use Shapecode\FUT\Client\Http\ClientFactoryInterface;
use Shapecode\FUT\Client\Locale\Locale;
use Shapecode\FUT\Client\Util\FutUtil;

/**
 * Class AbstractCore
 *
 * @package Shapecode\FUT\Client\Api
 * @author  Shapecode
 */
abstract class AbstractCore implements CoreInterface
{

    protected $sku = 'FUT19WEB';
    protected $clientVersion = 1;

    /** @var string */
    protected $ultimateApiUrl;

    /** @var AccountInterface */
    protected $account;

    /** @var ConfigInterface */
    protected $config;

    /** @var Locale */
    protected $locale;

    /** @var Pin */
    protected $pin;

    /** @var ClientFactory */
    protected $clientFactory;

    /**
     * @param AccountInterface            $account
     * @param ConfigInterface|null        $config
     * @param ClientFactoryInterface|null $clientFactory
     */
    public function __construct(AccountInterface $account, ConfigInterface $config = null, ClientFactoryInterface $clientFactory = null)
    {
        $this->account = $account;
        $this->config = $config ?: new Config();

        $this->clientFactory = $clientFactory ?: new ClientFactory();
        $this->clientFactory->setConfig($this->config);

        $this->locale = new Locale($account->getCredentials()->getLocale());
        $this->pin = new Pin($this->getAccount(), $this->getClientFactory());
    }

    /**
     * @return ClientFactory
     */
    public function getClientFactory()
    {
        return $this->clientFactory;
    }

    /**
     * @inheritdoc
     */
    public function login($code = null)
    {
        $account = $this->getAccount();
        $credentials = $account->getCredentials();
        $locale = $this->locale;

        /** @var string|null|UriInterface $url */
        $url = null;

        $headers = [];

        $response = $this->simpleRequest('GET', 'https://accounts.ea.com/connect/auth', [
            'query'    => [
                'prompt'        => 'login',
                'accessToken'   => '',
                'client_id'     => self::CLIENT_ID,
                'response_type' => 'token',
                'display'       => 'web2/login',
                'locale'        => $credentials->getLocale(),
                'release_type'  => 'prod',
                'redirect_uri'  => 'https://www.easports.com/fifa/ultimate-team/web-app/auth.html',
                'scope'         => 'basic.identity offline signin'
            ],
            'headers'  => $headers,
            'on_stats' => function (TransferStats $stats) use (&$url) {
                $url = $stats->getEffectiveUri();
            }
        ]);

        if ($url !== 'https://www.easports.com/fifa/ultimate-team/web-app/auth.html') {
            $headers['Referer'] = $url->__toString();
            $response = $this->simpleRequest('POST', $url, [
                'form_params' => [
                    'email'              => $credentials->getEmail(),
                    'password'           => $credentials->getPassword(),
                    'country'            => $credentials->getCountry(),
                    'phoneNumber'        => '',
                    'passwordForPhone'   => '',
                    'gCaptchaResponse'   => '',
                    'isPhoneNumberLogin' => 'false',
                    'isIncompletePhone'  => '',
                    '_rememberMe'        => 'on',
                    'rememberMe'         => 'on',
                    '_eventId'           => 'submit'
                ],
                'headers'     => $headers,
                'on_stats'    => function (TransferStats $stats) use (&$url) {
                    $url = $stats->getEffectiveUri();
                }
            ]);
            $responseContent = $response->getBody()->getContents();

            if (strpos($responseContent, $locale->get('login.incorrect_credentials')) !== false) {
                throw new IncorrectCredentialsException($response);
            }

            if (strpos($responseContent, $locale->get('login.redirect_uri')) !== false) {
                $response = $this->simpleRequest('GET', $url->__toString() . '&_eventId=end', [
                    'headers'  => $headers,
                    'on_stats' => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getEffectiveUri();
                    },
                ]);
                $responseContent = $response->getBody()->getContents();
            }

            if (strpos($responseContent, $locale->get('login.login_verification')) !== false) {
                $response = $this->simpleRequest('POST', $url->__toString(), [
                    'headers'     => $headers,
                    'form_params' => [
                        'codeType' => 'EMAIL',
                        '_eventId' => 'submit'
                    ],
                    'on_stats'    => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getEffectiveUri();
                    }
                ]);
                $responseContent = $response->getBody()->getContents();
            }

            if (strpos($responseContent, $locale->get('login.security_code')) !== false) {
                if ($code === null) {
                    throw new ProvideSecurityCodeException($response);
                }

                $headers['Referer'] = $url->__toString();
                $response = $this->simpleRequest('POST', str_replace('s3', 's4', $url->__toString()), [
                    'headers'     => $headers,
                    'form_params' => [
                        'oneTimeCode'      => $code,
                        '_trustThisDevice' => 'on',
                        'trustThisDevice'  => 'on',
                        '_eventId'         => 'submit'
                    ],
                    'on_stats'    => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getEffectiveUri();
                    }
                ]);
                $responseContent = $response->getBody()->getContents();

                if (strpos($responseContent, $locale->get('login.incorrect_code_1')) !== false ||
                    strpos($responseContent, $locale->get('login.incorrect_code_2')) !== false) {
                    throw new IncorrectSecurityCodeException($response);
                }

//                if (strpos($responseContent, 'Set Up an App Authenticator') !== false) {
//                    $response = $account->request('POST', str_replace('s3', 's4', $url->__toString()), [
//                        'form_params' => [
//                            'appDevice' => 'IPHONE',
//                            '_eventId'  => 'cancel'
//                        ],
//                        'headers'     => $headers,
//                        'on_stats'    => function (TransferStats $stats) use (&$url) {
//                            $url = $stats->getEffectiveUri();
//                        }
//                    ]);
//                    $responseContent = $response->getBody()->getContents();
//                }
            }
        }

        if (empty($url->getFragment())) {
            throw new AuthFailedException($response);
        }

        parse_str($url->getFragment(), $matches);

        $accessToken = $matches['access_token'];
        $tokenType = $matches['token_type'];
        $expiresAt = new \DateTime('+' . $matches['expires_in'] . ' seconds');

        $this->simpleRequest('GET', 'https://www.easports.com/fifa/ultimate-team/web-app/');

        $headers['Referer'] = 'https://www.easports.com/fifa/ultimate-team/web-app/';
        $headers['Accept'] = 'application/json';
        $headers['Authorization'] = $tokenType . ' ' . $accessToken;

        $response = $this->simpleRequest('GET', 'https://gateway.ea.com/proxy/identity/pids/me', [
            'headers' => $headers
        ]);
        $responseContent = json_decode($response->getBody()->getContents(), true);

        $nucleus_id = $responseContent['pid']['externalRefValue'];
        $dob = $responseContent['pid']['dob'];

        unset($headers['Authorization']);

        $headers['Easw-Session-Data-Nucleus-Id'] = $nucleus_id;

        //shards
        try {
            $response = $this->simpleRequest('GET', 'https://' . self::AUTH_URL . '/ut/shards/v2', [
                'headers' => $headers
            ]);
            $responseContent = $response->getBody()->getContents();
        } catch (RequestException $e) {
            throw new ServerDownException($e->getResponse(), $e);
        }

        //personas
        try {
            $response = $this->simpleRequest('GET', $this->getFifaApiUrl() . '/user/accountinfo', [
                'headers' => $headers,
                'query'   => [
                    'filterConsoleLogin'    => 'true',
                    'sku'                   => $this->sku,
                    'returningUserGameYear' => '2019'
                ]
            ]);
            $responseContent = json_decode($response->getBody()->getContents(), true);
        } catch (ConnectException $e) {
            throw new ServerDownException($e->getResponse(), $e);
        }

        if (!isset($responseContent['userAccountInfo']['personas'])) {
            throw new NoPersonaException($response);
        }

        $personasValues = array_values($responseContent['userAccountInfo']['personas']);
        $persona = $personasValues[0];
        $persona_id = $persona['personaId'] ?? null;

        //validate persona found.
        if ($persona_id === null) {
            throw new NoPersonaException($response);
        }

        //validate user state
        switch ($persona['userState']) {
            case 'RETURNING_USER_EXPIRED':
                throw new UserExpiredException($response);
                break;
        }

        //authorization
        unset($headers['Easw-Session-Data-Nucleus-Id']);
        $headers['Origin'] = 'http://www.easports.com';

        $response = $this->simpleRequest('GET', 'https://accounts.ea.com/connect/auth', [
            'headers' => $headers,
            'query'   => [
                'client_id'     => 'FOS-SERVER',
                'redirect_uri'  => 'nucleus:rest',
                'response_type' => 'code',
                'access_token'  => $accessToken
            ]
        ]);
        $responseContent = json_decode($response->getBody()->getContents(), true);

        $auth_code = $responseContent['code'];

        $headers['Content-Type'] = 'application/json';
        $response = $this->simpleRequest('POST', $this->getFutAuthUrl(), [
            'headers' => $headers,
            'body'    => json_encode([
                'isReadOnly'       => false,
                'sku'              => $this->sku,
                'clientVersion'    => $this->clientVersion,
                'nucleusPersonaId' => $persona_id,
                'gameSku'          => $this->getGameSku(),
                'locale'           => 'en-US',
                'method'           => 'authcode',
                'priorityLevel'    => 4,
                'identification'   => [
                    'authCode'    => $auth_code,
                    'redirectUrl' => 'nucleus:rest'
                ]
            ])
        ]);

        if ($response->getStatusCode() === 401) {
            throw new MaxSessionsException($response);
        }

        if ($response->getStatusCode() === 500) {
            throw new ServerDownException($response);
        }

        $responseContent = json_decode($response->getBody()->getContents(), true);
        if (isset($responseContent['reason'])) {
            switch ($responseContent['reason']) {
                case 'multiple session':
                case 'max sessions':
                    throw new MaxSessionsException($response);
                    break;
                case 'doLogin: doLogin failed':
                    throw new AuthFailedException($response);
                    break;
                default:
                    throw new FutResponseException($responseContent['reason'], $response);
                    break;
            }
        }

        $phishingToken = $responseContent['phishingToken'];
        $sid = $responseContent['sid'];

        $this->setSessionData($persona_id, $nucleus_id, $phishingToken, $sid, $dob, $accessToken, $tokenType, $expiresAt);

        $this->getPin()->sendEvent('login', 'success');
        $this->getPin()->sendEvent('page_view', 'Hub - Home');
        $this->getPin()->send([
            $this->getPin()->event('connection'),
            $this->getPin()->event('boot_end', false, false, false, 'normal')
        ]);

        // return info
        return [
            'email'          => $credentials->getEmail(),
            'access_token'   => $accessToken,
            'token_type'     => $tokenType,
            'nucleus_id'     => $nucleus_id,
            'persona_id'     => $persona_id,
            'phishing_token' => $phishingToken,
            'session_id'     => $sid,
            'dob'            => $dob,
            'expiresAt'      => $expiresAt,
        ];
    }

    /**
     * @param           $persona_id
     * @param           $nucleus_id
     * @param           $phishingToken
     * @param           $sid
     * @param           $dob
     * @param           $access_token
     * @param           $token_type
     * @param \DateTime $expiresAt
     */
    protected function setSessionData($persona_id, $nucleus_id, $phishingToken, $sid, $dob, $access_token, $token_type, \DateTime $expiresAt)
    {
        $session = Session::create($persona_id, $nucleus_id, $phishingToken, $sid, $dob, $access_token, $token_type, $expiresAt);
        $this->getAccount()->setSession($session);
    }

    /**
     * @inheritdoc
     */
    public function logout()
    {
        $this->getPin()->sendEvent('page_view', 'Settings');

        $this->request('GET', 'https://accounts.ea.com/connect/logout', null, [
            'client_id'    => self::CLIENT_ID,
            'redirect_uri' => 'https://www.easports.com/fifa/ultimate-team/web-app/auth.html',
            'release_type' => 'prod'
        ]);
        $this->request('GET', 'https://www.easports.com/signout', null, [
            'ct' => time()
        ]);
        $this->request('GET', 'https://accounts.ea.com/connect/clearsid', null, [
            'ct' => time()
        ]);

        $this->resetSession();
    }

    /**
     *
     */
    protected function resetSession()
    {
        $this->getAccount()->resetSession();
        $this->pin = null;
    }

    /**
     * @inheritdoc
     */
    public function searchDefinition($asset_id, $start = 0, $count = 20)
    {
        $params = [
            'defId' => FutUtil::getBaseId($asset_id),
            'start' => $start,
            'type'  => 'player',
            'count' => $count
        ];

        $response = $this->request('GET', 'defid', [], $params);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function search(array $params = [], $pageSize = 20, $start = 0)
    {
        if ($start === 0) {
            $this->getPin()->sendEvent('page_view', 'Transfer Market Search');
        }

        $params['start'] = $start;
        $params['num'] = $pageSize;

        if (!isset($params['type'])) {
            $params['type'] = 'player';
        }

        $response = $this->request('GET', '/transfermarket', [], $params);

        if ($start === 0) {
            $this->getPin()->send([
                $this->getPin()->event('page_view', 'Transfer Market Results - List View'),
                $this->getPin()->event('page_view', 'Item - Detail View')
            ]);
        }

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function bid($tradeId, $price)
    {
        $response = $this->request('PUT', '/trade/' . $tradeId . '/bid', [
            'bid' => $price
        ]);

        $this->getPin()->send([
            $this->getPin()->event('connection'),
            $this->getPin()->event('boot_end', false, false, false, 'normal')
        ]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function usermassInfo()
    {
        $response = $this->request('GET', '/usermassinfo');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function credits()
    {
        $response = $this->request('GET', '/user/credits');
        $data = $this->getResponseContent($response);

        if (isset($data['credits'])) {
            return $data['credits'];
        }

        return 0;
    }

    /**
     * @inheritdoc
     */
    public function club(array $params = [])
    {
        if (!isset($params['sort'])) {
            $params['sort'] = 'desc';
        }

        if (!isset($params['start'])) {
            $params['start'] = 0;
        }

        if (!isset($params['count'])) {
            $params['count'] = 91;
        }

        if (!isset($params['type'])) {
            $params['type'] = 'player';
        }

        $response = $this->request('GET', '/club', [], $params);

        if ($params['start'] === 0) {
            switch ($params['type']) {
                case 'player':
                    $this->getPin()->sendEvent('page_view', 'Club - Players - List View');
                    break;
                case 'stadium':
                    $this->getPin()->send([
                        $this->getPin()->event('page_view', 'Club - Club Items - List View'),
                        $this->getPin()->event('page_view', 'Item - Detail View'),
                    ]);
                    break;
                case 'staff':
                    $this->getPin()->sendEvent('page_view', 'Club - Staff - List View');
                    break;
                case 'item':
                    $this->getPin()->sendEvent('page_view', 'Club - Club Items - List View');
                    break;
                default:
                    $this->getPin()->sendEvent('page_view', 'Club - Club Items - List View');
                    break;
            }
        }

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function players(array $params = [])
    {
        $params['type'] = 'player';

        return $this->club($params);
    }

    /**
     * @inheritdoc
     */
    public function stadiums(array $params = [])
    {
        $params['type'] = 'stadium';

        return $this->club($params);
    }

    /**
     * @inheritdoc
     */
    public function kits(array $params = [])
    {
        $params['type'] = 'kit';

        return $this->club($params);
    }

    /**
     * @inheritdoc
     */
    public function staffs(array $params = [])
    {
        $params['type'] = 'staff';

        return $this->club($params);
    }

    /**
     * @inheritdoc
     */
    public function badges(array $params = [])
    {
        $params['type'] = 'badge';

        return $this->club($params);
    }

    /**
     * @inheritdoc
     */
    public function balls(array $params = [])
    {
        $params['type'] = 'ball';

        return $this->club($params);
    }

    /**
     * @inheritdoc
     */
    public function clubStaff()
    {
        $response = $this->request('GET', '/club/stats/staff');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function clubConsumables()
    {
        $response = $this->request('GET', '/club/consumables/development');

        $this->getPin()->sendEvent('page_view', 'Hub - Club');
        $this->getPin()->sendEvent('page_view', 'Club - Consumables');
        $this->getPin()->sendEvent('page_view', 'Club - Consumables - List View');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function squad($squadId = 0)
    {
        $this->getPin()->sendEvent('page_view', 'Hub - Squads');

        $personaId = $this->getAccount()->getSession()->getPersona();
        $response = $this->request('GET', '/squad/' . $squadId . '/user/' . $personaId);

        $this->getPin()->sendEvent('page_view', 'Squads - Squad Overview');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function tradeStatus($tradeId)
    {
        $response = $this->request('GET', '/trade/status', null, [
            'tradeIds' => $tradeId
        ]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function tradepile()
    {
        $response = $this->request('GET', '/tradepile');

        $this->getPin()->sendEvent('page_view', 'Transfer List - List View');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function watchlist()
    {
        $response = $this->request('GET', '/watchlist');

        $this->getPin()->sendEvent('page_view', 'Transfer Targets - List View');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function unassigned()
    {
        $response = $this->request('GET', '/purchased/items');

        $this->getPin()->sendEvent('page_view', 'Unassigned Items - List View');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function sell($itemId, $bid, $bin, $duration = 3600)
    {
        $options = [
            'itemData'    => [
                'id' => (int)$itemId
            ],
            'startingBid' => (int)$bid,
            'duration'    => $duration,
            'buyNowPrice' => (int)$bin,
        ];

        $response = $this->request('POST', '/auctionhouse', $options);

        $data = $this->getResponseContent($response);

        $this->sleep(250, 750);

        $tradeStatus = $this->tradeStatus($data['id']);

        return $tradeStatus['auctionInfo'][0];
    }

    /**
     * @inheritdoc
     */
    public function quickSell($itemId)
    {
        $response = $this->request('DELETE', '/item', null, [
            'itemIds' => $itemId
        ]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function removeSold($tradeId)
    {
        $response = $this->request('DELETE', '/trade/' . $tradeId);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function watchlistDelete($tradeId)
    {
        if (is_array($tradeId)) {
            $tradeId = implode(',', $tradeId);
        }

        $response = $this->request('DELETE', '/watchlist', null, [
            'tradeId' => $tradeId
        ]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function sendToTradepile($itemId)
    {
        return $this->sendToPile('trade', $itemId);
    }

    /**
     * @inheritdoc
     */
    public function sendToClub($itemId)
    {
        return $this->sendToPile('club', $itemId);
    }

    /**
     * @inheritdoc
     */
    public function sendToWatchList($tradeId)
    {
        $response = $this->request('PUT', '/watchlist', [
            'auctionInfo' => [
                [
                    'id' => $tradeId
                ]
            ]
        ]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function priceRange($definitionId)
    {
        if (is_array($definitionId)) {
            $definitionId = implode(',', $definitionId);
        }

        $response = $this->request('POST', '/marketdata/pricelimits?defId=' . $definitionId);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function relist()
    {
        $response = $this->request('PUT', '/auctionhouse/relist');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function applyConsumable($itemId, $resourceId)
    {
        $response = $this->request('POST', '/item/resource/' . $resourceId, [
            'apply' => [
                [
                    'id' => $itemId
                ]
            ]
        ]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function keepalive()
    {
        return $this->credits();
    }

    /**
     * @inheritdoc
     */
    public function pileSize()
    {
        $usermassinfo = $this->usermassInfo();
        $data = $usermassinfo['pileSizeClientData']['entries'];

        return [
            'tradepile' => $data[0]['value'],
            'watchlist' => $data[2]['value']
        ];
    }

    /**
     * @inheritdoc
     */
    public function buyPack($packId, $currency = 'COINS')
    {
        $this->getPin()->sendEvent('page_view', 'Hub - Store');

        $response = $this->request('POST', '/purchased/items', [
            'packId'   => $packId,
            'currency' => $currency
        ]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function openPack($packId)
    {
        $response = $this->request('POST', '/purchased/items', [
            'packId'      => $packId,
            'currency'    => 0,
            'usePreOrder' => true
        ]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function squadBuildingSets()
    {
        $response = $this->request('GET', '/sbs/sets');

        $this->getPin()->sendEvent('page_view', 'Hub - SBC');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function squadBuildingChallenges($setId)
    {
        $response = $this->request('GET', '/sbs/setId/' . $setId . '/challenges');

        $this->getPin()->sendEvent('page_view', 'SBC - Challenges');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function objectives($scope = 'all')
    {
        $response = $this->request('GET', '/user/dynamicobjectives', null, ['scope' => $scope]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    protected function sendToPile($pile, $item_id = null)
    {
        $response = $this->request('PUT', '/item', [
            'itemData' => [
                [
                    'id'   => $item_id,
                    'pile' => $pile,
                ]
            ]
        ]);

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function getCaptchaData()
    {
        $response = $this->request('GET', '/captcha/fun/data');

        return $this->getResponseContent($response);
    }

    /**
     * @inheritdoc
     */
    public function validateCaptcha($token)
    {
        return $this->request('POST', '/captcha/fun/validate', [
            'funCaptchaToken' => $token
        ]);
    }

    /**
     * @return AccountInterface
     */
    protected function getAccount()
    {
        return $this->account;
    }

    /**
     * @return PinInterface
     */
    protected function getPin()
    {
        return $this->pin;
    }

    /**
     * @return string
     */
    protected function getFifaApiUrl()
    {
        return $this->getFutApiUrl() . '/game/fifa19';
    }

    /**
     * @return string
     */
    protected function getFutAuthUrl()
    {
        return $this->getFutApiUrl() . '/auth';
    }

    /**
     * @return string
     */
    protected function getFutApiUrl()
    {
        if ($this->ultimateApiUrl === null) {
            $platform = $this->getAccount()->getCredentials()->getPlatform();

            $host = self::FUT_HOSTS[$platform];

            $this->ultimateApiUrl = 'https://' . $host . '/ut';
        }

        return $this->ultimateApiUrl;
    }

    /**
     * @return string
     */
    protected function getGameSku()
    {
        return FutUtil::getGameSku($this->getAccount()->getCredentials()->getPlatform());
    }

    /**
     * @param int|null $min
     * @param int|null $max
     */
    protected function sleep($min = null, $max = null)
    {
        usleep($this->getConfig()->getRandomDelayTime($min, $max));
    }

    /**
     * @param       $method
     * @param       $url
     * @param array $options
     *
     * @return mixed|ResponseInterface
     * @throws \Http\Client\Exception
     */
    protected function simpleRequest($method, $url, array $options = [])
    {
        return $this->getClientFactory()->request($this->getAccount(), $method, $url, $options);
    }

    /**
     * @inheritdoc
     */
    protected function request($method, $url, $body = null, array $params = [], array $headers = [])
    {
        if (strpos($url, 'http') === false) {
            $url = $this->getFifaApiUrl() . $url;
        }

        if ($this->getConfig()->isDelay() === true) {
            $this->sleep();
        }

        $method = strtoupper($method);

        if ($this->getAccount()->getSession() === null) {
            throw new NoSessionException();
        }

        $account = $this->getAccount();
        $session = $account->getSession();

        $headers['Easw-Session-Data-Nucleus-Id'] = $session->getNucleus();
        $headers['X-UT-SID'] = $session->getSession();
        $headers['X-UT-PHISHING-TOKEN'] = $session->getPhishing();

        switch ($method) {
            case 'GET':
                $params['_'] = time();
                break;
        }

        $options = [
            'headers' => $headers,
            'query'   => $params,
        ];

        if ($body !== null) {
            if (is_array($body)) {
                $body = json_encode($body);
            }

            $options['body'] = $body;
        }

        $response = $this->simpleRequest($method, $url, $options);

        $this->handleInvalidResponse($response);

        return $response;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return mixed|string
     */
    protected function getResponseContent(ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();

        if ($content !== '') {
            $content = json_decode($content, true);
        }

        return $content;
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws CaptchaException
     * @throws PermissionDeniedException
     * @throws ServerDownException
     * @throws SessionExpiredException
     * @throws TemporaryBanException
     * @throws ToManyRequestsException
     * @throws TransferMarketDisabledException
     */
    protected function handleInvalidResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() === 200) {
            return;
        }

        switch ($response->getStatusCode()) {

            // session expired
            case 401:
                $this->resetSession();

                throw new SessionExpiredException($response);
                break;

            // to many requests
            case 426:
            case 429:
                throw new ToManyRequestsException($response);
                break;

            // captcha
            case 458:
                $this->captchaReceived();
                $this->getPin()->sendEvent('error');

                throw new CaptchaException($response);
                break;

            // permission denied
            case 460:
            case 461:
                throw new PermissionDeniedException($response);
                break;

            // transfer market disabled
            case 494:
                throw new TransferMarketDisabledException($response);
                break;

            // ban
            case 512:
            case 521:
                throw new TemporaryBanException($response);
                break;

            // server down
            case 500:
                throw new ServerDownException($response);
                break;
        }
    }

    /**
     *
     */
    protected function captchaReceived()
    {
        // nothing to do in default
    }

    /**
     * @return Config
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setConfig($name, $value)
    {
        $this->config->setOption($name, $value);
    }

}
