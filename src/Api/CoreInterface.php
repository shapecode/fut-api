<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Api;

use Shapecode\FUT\Client\Http\ClientCall;

/**
 * Interface CoreInterface
 */
interface CoreInterface
{
    public const FUT_HOSTS       = [
        'pc'   => 'utas.external.s2.fut.ea.com:443',
        'ps3'  => 'utas.external.s2.fut.ea.com:443',
        'ps4'  => 'utas.external.s2.fut.ea.com:443',
        'xbox' => 'utas.external.s3.fut.ea.com:443',
    ];
    public const REQUEST_HEADERS = [
        'Accept'            => '*/*',
        'Accept-Encoding'   => 'gzip, deflate, br',
        'Accept-Language'   => 'en-US,de;q=0.9,en-US;q=0.8,en;q=0.7,lb;q=0.6',
        'Cache-Control'     => 'no-cache',
        'Connection'        => 'keep-alive',
        'DNT'               => '1',
        'Origin'            => 'https://www.easports.com',
        'Pragma'            => 'no-cache',
        'Referer'           => 'https://www.easports.com/fifa/ultimate-team/web-app/',
        'Sec-Fetch-Mode'    => 'cors',
        'Sec-Fetch-Site'    => 'cross-site',
    ];
    public const AUTH_URL        = 'utas.mob.v4.fut.ea.com:443';
    public const CLIENT_ID       = 'FIFA-20-WEBCLIENT';
    public const SKU             = 'FUT20WEB';

    /**
     * @return mixed[]
     */
    public function login(?string $code = null) : array;

    public function logout() : void;

    /**
     * @return mixed
     */
    public function searchDefinition(int $assetId, int $start = 0, int $count = 20);

    /**
     * @param mixed[] $params
     *
     * lev: level
     * cat: category
     * definitionId:
     * micr: min bid price
     * macr: max bid price
     * minb: min bin price
     * maxb: max bin price
     * leag: league
     * team: club
     * pos: position
     * zone:
     * nation:
     * rare: rare type
     * playStyle: player style
     *
     * @return mixed
     */
    public function search(array $params = [], int $pageSize = 20, int $start = 0);

    /**
     * @param mixed $tradeId
     * @param mixed $price
     *
     * @return mixed
     */
    public function bid($tradeId, $price);

    /**
     * @return mixed
     */
    public function usermassInfo();

    /**
     * @return mixed
     */
    public function credits();

    /**
     * @param mixed[] $params
     *
     * @return mixed
     *
     * level
     * defId
     * start
     * count
     * sort
     */
    public function club(array $params = []);

    /**
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    public function players(array $params = []) : array;

    /**
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    public function stadiums(array $params = []) : array;

    /**
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    public function kits(array $params = []) : array;

    /**
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    public function staffs(array $params = []) : array;

    /**
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    public function badges(array $params = []) : array;

    /**
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    public function balls(array $params = []) : array;

    /**
     * @return mixed
     */
    public function clubStaff();

    /**
     * @return mixed
     */
    public function clubConsumables();

    /**
     * @return mixed
     */
    public function squad(int $squadId = 0);

    /**
     * @param mixed $tradeId
     *
     * @return mixed
     */
    public function tradeStatus($tradeId);

    /**
     * @return mixed
     */
    public function tradepile();

    /**
     * @return mixed
     */
    public function watchlist();

    /**
     * @param mixed $tradeId
     *
     * @return mixed
     */
    public function watchlistDelete($tradeId);

    /**
     * @return mixed
     */
    public function unassigned();

    /**
     * @param mixed $id
     * @param mixed $bid
     * @param mixed $bin
     *
     * @return mixed
     */
    public function sell($id, $bid, $bin, int $duration = 3600);

    /**
     * @param mixed $itemId
     *
     * @return mixed
     */
    public function quickSell($itemId);

    /**
     * @param mixed $tradeId
     *
     * @return mixed
     */
    public function removeSold($tradeId);

    /**
     * @param mixed $itemId
     *
     * @return mixed
     */
    public function sendToTradepile($itemId);

    /**
     * @param mixed $itemId
     *
     * @return mixed
     */
    public function sendToClub($itemId);

    /**
     * @param mixed $tradeId
     *
     * @return mixed
     */
    public function sendToWatchList($tradeId);

    /**
     * @param mixed $definitionId
     *
     * @return mixed
     */
    public function priceRange($definitionId);

    /**
     * @return mixed
     */
    public function relist();

    /**
     * @param mixed $itemId
     * @param mixed $resourceId
     *
     * @return mixed
     */
    public function applyConsumable($itemId, $resourceId);

    /**
     * @return mixed
     */
    public function keepalive();

    /**
     * @return mixed
     */
    public function pileSize();

    /**
     * @param mixed $packId
     *
     * @return mixed
     */
    public function buyPack($packId, string $currency = 'COINS');

    /**
     * @param mixed $packId
     *
     * @return mixed
     */
    public function openPack($packId);

    /**
     * @return mixed
     */
    public function squadBuildingSets();

    /**
     * @param mixed $setId
     *
     * @return mixed
     */
    public function squadBuildingChallenges($setId);

    /**
     * @return mixed
     */
    public function objectives(string $scope = 'all');

    /**
     * @return mixed|string
     */
    public function getCaptchaData();

    public function validateCaptcha(string $token) : ClientCall;

    public function phishingQuestion() : ClientCall;

    /**
     * @param mixed $answer
     */
    public function phishingValidate($answer) : ClientCall;
}
