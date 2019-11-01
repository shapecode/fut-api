<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\TradeItem;
use Shapecode\FUT\Client\Items\TradeItemInterface;
use function count;

class BidResponse
{
    /** @var int|null */
    private $credits;

    /** @var TradeItem[] */
    private $auctions = [];

    /**
     * @param TradeItem[] $auctions
     */
    public function __construct(array $auctions, ?int $credits)
    {
        $this->auctions = $auctions;
        $this->credits  = $credits;
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

    public function hasAuction(int $index) : bool
    {
        return isset($this->auctions[$index]);
    }

    public function getAuction(int $index) : ?TradeItemInterface
    {
        if (! $this->hasAuction($index)) {
            return null;
        }

        return $this->auctions[$index];
    }

    public function getCredits() : ?int
    {
        return $this->credits;
    }
}
