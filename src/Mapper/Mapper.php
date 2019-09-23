<?php

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

/**
 * Class Mapper
 *
 * @package Shapecode\FUT\Client\Mapper
 * @author  Nikita Loges
 */
class Mapper
{

    public function createTransferMarketSearch($search): MarketSearchResponse
    {
        $as = $search['auctionInfo'] ?? [];
        $bs = $search['bidTokens'] ?? [];

        $auctions = [];
        $bidTokens = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        return new MarketSearchResponse($auctions, $bidTokens);
    }

    public function createBidResult($data): BidResponse
    {
        $as = $data['auctionInfo'] ?? [];
        $credits = $data['credits'] ?? null;

        $auctions = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        return new BidResponse($auctions, $credits);
    }

    public function createUnassignedResponse($data): UnassignedResponse
    {
        $itemData = $data['itemData'] ?? [];
        $duplicateItemIdList = $data['duplicateItemIdList'] ?? [];

        $items = [];
        $duplicates = [];

        foreach ($itemData as $a) {
            $items[] = $this->createItem($a);
        }

        foreach ($duplicateItemIdList as $a) {
            $duplicates[] = $this->createDuplicateItem($a);
        }

        return new UnassignedResponse($items, $duplicates);
    }

    public function createWatchlistResponse($data): WatchlistResponse
    {
        $credits = $data['credits'] ?? null;
        $total = $data['total'] ?? null;
        $as = $data['auctionInfo'] ?? [];

        $auctions = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        return new WatchlistResponse($total, $credits, $auctions);
    }

    public function createTradepileResponse($data): TradepileResponse
    {
        $credits = $data['credits'] ?? null;
        $as = $data['auctionInfo'] ?? [];
        $bs = $search['bidTokens'] ?? [];

        $auctions = [];
        $bidTokens = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        return new TradepileResponse($credits, $auctions, $bidTokens);
    }

    public function createTradeStatusResponse($data): TradeStatusResponse
    {
        $credits = $data['credits'] ?? null;
        $as = $data['auctionInfo'] ?? [];
        $bs = $search['bidTokens'] ?? [];
        $cs = $search['currencies'] ?? [];

        $auctions = [];
        $bidTokens = [];
        $currencies = [];

        foreach ($as as $a) {
            $auctions[] = $this->createTradeItem($a);
        }

        foreach ($cs as $a) {
            $currencies[] = $this->createCurrencyValue($a);
        }

        return new TradeStatusResponse($credits, $auctions, $bidTokens, $currencies);
    }

    public function createTradeItem($data): TradeItem
    {
        $item = $this->createItem($data['itemData']);

        return new TradeItem($data, $item);
    }

    public function createCurrencyValue($data): CurrencyValue
    {
        return new CurrencyValue($data);
    }

    public function createItem($data): Item
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

    public function createDuplicateItem($data): DuplicateItem
    {
        return new DuplicateItem($data['itemId'], $data['duplicateItemId']);
    }
}
