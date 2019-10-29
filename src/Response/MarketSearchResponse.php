<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\TradeItem;
use function count;

class MarketSearchResponse
{
    /** @var TradeItem[] */
    protected $auctions = [];

    /** @var mixed[] */
    protected $bidTokens = [];

    /**
     * @param TradeItem[] $auctions
     * @param mixed[]     $bidTokens
     */
    public function __construct(array $auctions, array $bidTokens)
    {
        $this->auctions  = $auctions;
        $this->bidTokens = $bidTokens;
    }

    /**
     * @return TradeItem[]
     */
    public function getAuctions() : array
    {
        return $this->auctions;
    }

    public function hasAuctions() : bool
    {
        return count($this->auctions) > 0;
    }

    /**
     * @return mixed[]
     */
    public function getBidTokens() : array
    {
        return $this->bidTokens;
    }
}
