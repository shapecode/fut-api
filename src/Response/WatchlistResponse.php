<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\TradeItem;

class WatchlistResponse
{
    private ?int $credits = null;

    private ?int $total = null;

    /** @var TradeItem[] */
    private array $auctions = [];

    /**
     * @param TradeItem[] $auctions
     */
    public function __construct(?int $total, ?int $credits, array $auctions)
    {
        $this->credits  = $credits;
        $this->auctions = $auctions;
        $this->total    = $total;
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

    public function getTotal(): ?int
    {
        return $this->total;
    }
}
