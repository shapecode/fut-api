<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\TradeItem;

class TradepileResponse
{
    private ?int $credits = null;

    /** @var TradeItem[] */
    private array $auctions = [];

    /** @var mixed[]  */
    private array $bidTokens = [];

    /**
     * @param TradeItem[] $auctions
     * @param mixed[]     $bidTokens
     */
    public function __construct(?int $credits, array $auctions, array $bidTokens)
    {
        $this->credits   = $credits;
        $this->auctions  = $auctions;
        $this->bidTokens = $bidTokens;
    }

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    /**
     * @return TradeItem[]
     */
    public function getAuctions(): array
    {
        return $this->auctions;
    }

    /**
     * @return mixed[]
     */
    public function getBidTokens(): array
    {
        return $this->bidTokens;
    }
}
