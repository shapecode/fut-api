<?php

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\TradeItem;

/**
 * Class TradepileResponse
 *
 * @package Shapecode\FUT\Client\Response
 * @author  Nikita Loges
 */
class TradepileResponse
{

    /** @var int|null */
    protected $credits;

    /** @var TradeItem[] */
    protected $auctions = [];

    /** @var array */
    protected $bidTokens = [];

    /**
     * @param int|null    $credits
     * @param TradeItem[] $auctions
     * @param array       $bidTokens
     */
    public function __construct(?int $credits, array $auctions, array $bidTokens)
    {
        $this->credits = $credits;
        $this->auctions = $auctions;
        $this->bidTokens = $bidTokens;
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
     * @return array
     */
    public function getBidTokens(): array
    {
        return $this->bidTokens;
    }
}
