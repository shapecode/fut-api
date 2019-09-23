<?php

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\TradeItem;

/**
 * Class WatchlistResponse
 *
 * @package Shapecode\FUT\Client\Response
 * @author  Nikita Loges
 */
class WatchlistResponse
{

    /** @var int|null */
    protected $credits;

    /** @var int|null */
    protected $total;

    /** @var TradeItem[] */
    protected $auctions = [];

    /**
     * @param int|null $total
     * @param int|null $credits
     * @param array    $auctions
     */
    public function __construct(?int $total, ?int $credits, array $auctions)
    {
        $this->credits = $credits;
        $this->auctions = $auctions;
        $this->total = $total;
    }

    /**
     * @return int|null
     */
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
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }
}
