<?php

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\TradeItem;

/**
 * Class MarketSearchResponse
 *
 * @package Shapecode\FUT\Client\Response
 * @author  Nikita Loges
 */
class MarketSearchResponse
{

    /** @var TradeItem[] */
    protected $auctions = [];

    /** @var array */
    protected $bidTokens = [];

    /**
     * @param array $auctions
     * @param array $bidTokens
     */
    public function __construct(array $auctions, array $bidTokens)
    {
        $this->auctions = $auctions;
        $this->bidTokens = $bidTokens;
    }

    /**
     * @return array
     */
    public function getAuctions(): array
    {
        return $this->auctions;
    }

    /**
     * @return array
     */
    public function getBidTokens(): array
    {
        return $this->bidTokens;
    }
}
