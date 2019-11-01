<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\TradeItem;
use Webmozart\Assert\Assert;
use function count;

class MarketSearchResponse extends AbstractResponse
{
    /** @var TradeItem[] */
    private $auctions = [];

    /** @var mixed[] */
    private $bidTokens = [];

    /**
     * @param mixed[]     $rawBody
     * @param TradeItem[] $auctions
     * @param mixed[]     $bidTokens
     */
    public function __construct(
        array $rawBody,
        array $auctions,
        array $bidTokens
    ) {
        parent::__construct($rawBody);

        Assert::allIsInstanceOf($auctions, TradeItem::class);

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
