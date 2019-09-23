<?php

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\TradeItem;

/**
 * Class BidResponse
 *
 * @package Shapecode\FUT\Client\Response
 * @author  Nikita Loges
 */
class BidResponse
{

    /** @var int|null */
    protected $credits;

    /** @var TradeItem[] */
    protected $auctions = [];

    /**
     * @param array    $auctions
     * @param int|null $credits
     */
    public function __construct(array $auctions, ?int $credits)
    {
        $this->auctions = $auctions;
        $this->credits = $credits;
    }

    /**
     * @return TradeItem[]
     */
    public function getAuctions(): array
    {
        return $this->auctions;
    }

    /**
     * @return bool
     */
    public function hasAuctions(): bool
    {
        return count($this->auctions) > 0;
    }

    /**
     * @return int|null
     */
    public function getCredits(): ?int
    {
        return $this->credits;
    }
}
