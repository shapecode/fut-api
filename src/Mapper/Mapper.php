<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Mapper;

use Shapecode\FUT\Client\Items\Contract;
use Shapecode\FUT\Client\Items\CurrencyValue;
use Shapecode\FUT\Client\Items\DuplicateItem;
use Shapecode\FUT\Client\Items\Health;
use Shapecode\FUT\Client\Items\Item;
use Shapecode\FUT\Client\Items\Kit;
use Shapecode\FUT\Client\Items\Player;
use Shapecode\FUT\Client\Items\TradeItem;
use Shapecode\FUT\Client\Response\BidResponse;
use Shapecode\FUT\Client\Response\MarketSearchResponse;
use Shapecode\FUT\Client\Response\TradepileResponse;
use Shapecode\FUT\Client\Response\TradeStatusResponse;
use Shapecode\FUT\Client\Response\UnassignedResponse;
use Shapecode\FUT\Client\Response\WatchlistResponse;

class Mapper
{
    /**
     * @param mixed[] $data
     */
    public function createTransferMarketSearch(array $data) : MarketSearchResponse
    {
        $as = $data['auctionInfo'] ?? [];
        $bs = $data['bidTokens'] ?? [];

        $auctions  = [];
        $bidTokens = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        return new MarketSearchResponse(
            $data,
            $auctions,
            $bidTokens
        );
    }

    /**
     * @param mixed[] $data
     */
    public function createBidResult(array $data) : BidResponse
    {
        $as      = $data['auctionInfo'] ?? [];
        $credits = $data['credits'] ?? null;

        $auctions = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        return new BidResponse($auctions, $credits);
    }

    /**
     * @param mixed[] $data
     */
    public function createUnassignedResponse(array $data) : UnassignedResponse
    {
        $itemData            = $data['itemData'] ?? [];
        $duplicateItemIdList = $data['duplicateItemIdList'] ?? [];

        $items      = [];
        $duplicates = [];

        foreach ($itemData as $a) {
            $items[] = $this->createItem($a);
        }

        foreach ($duplicateItemIdList as $a) {
            $duplicates[] = $this->createDuplicateItem($a);
        }

        return new UnassignedResponse($items, $duplicates);
    }

    /**
     * @param mixed[] $data
     */
    public function createWatchlistResponse(array $data) : WatchlistResponse
    {
        $credits = $data['credits'] ?? null;
        $total   = $data['total'] ?? null;
        $as      = $data['auctionInfo'] ?? [];

        $auctions = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        return new WatchlistResponse($total, $credits, $auctions);
    }

    /**
     * @param mixed[] $data
     */
    public function createTradepileResponse(array $data) : TradepileResponse
    {
        $credits = $data['credits'] ?? null;
        $as      = $data['auctionInfo'] ?? [];
        $bs      = $search['bidTokens'] ?? [];

        $auctions  = [];
        $bidTokens = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        return new TradepileResponse($credits, $auctions, $bidTokens);
    }

    /**
     * @param mixed[] $data
     */
    public function createTradeStatusResponse(array $data) : TradeStatusResponse
    {
        $credits = $data['credits'] ?? null;
        $as      = $data['auctionInfo'] ?? [];
        $bs      = $data['bidTokens'] ?? [];
        $cs      = $data['currencies'] ?? [];

        $auctions   = [];
        $bidTokens  = [];
        $currencies = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        foreach ($cs as $a) {
            $currencies[] = $this->createCurrencyValue($a);
        }

        return new TradeStatusResponse($credits, $auctions, $bidTokens, $currencies);
    }

    /**
     * @param mixed[] $data
     */
    public function createTradeItem(array $data) : TradeItem
    {
        $item = $this->createItem($data['itemData']);

        return new TradeItem($data, $item);
    }

    /**
     * @param mixed[] $data
     */
    public function createCurrencyValue(array $data) : CurrencyValue
    {
        return new CurrencyValue($data);
    }

    /**
     * @param mixed[] $data
     */
    public function createItem(array $data) : Item
    {
        $itemType = $data['itemType'] ?? null;

        if ($itemType === 'player') {
            return new Player($data);
        }

        if ($itemType === 'health') {
            return new Health($data);
        }

        if ($itemType === 'contract') {
            return new Contract($data);
        }

        if ($itemType === 'kit') {
            return new Kit($data);
        }

        return new Item($data);
    }

    /**
     * @param mixed[] $data
     */
    public function createDuplicateItem(array $data) : DuplicateItem
    {
        return new DuplicateItem($data['itemId'], $data['duplicateItemId']);
    }
}
