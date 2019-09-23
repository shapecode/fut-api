<?php

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\CurrencyValue;
use Shapecode\FUT\Client\Items\TradeItemInterface;

/**
 * Class TradeStatusResponse
 *
 * @package Shapecode\FUT\Client\Response
 * @author  Nikita Loges
 */
class TradeStatusResponse
{

    /** @var null|int */
    protected $credits;

    /** @var TradeItemInterface[] */
    protected $auctions;

    /** @var array */
    protected $bidTokens;

    /** @var CurrencyValue[] */
    protected $currencies;

    /**
     * @param int|null             $credits
     * @param TradeItemInterface[] $auctions
     * @param array                $bidTokens
     * @param CurrencyValue[]      $currencies
     */
    public function __construct(?int $credits, array $auctions, array $bidTokens, array $currencies)
    {
        $this->credits = $credits;
        $this->auctions = $auctions;
        $this->bidTokens = $bidTokens;
        $this->currencies = $currencies;
    }

    /**
     * @return int|null
     */
    public function getCredits(): ?int
    {
        return $this->credits;
    }

    /**
     * @return TradeItemInterface[]
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
     * @param int $index
     *
     * @return TradeItemInterface|null
     */
    public function getAuction(int $index): ?TradeItemInterface
    {
        if (!$this->hasAuction($index)) {
            return null;
        }

        return $this->auctions[$index];
    }

    /**
     * @param int $index
     *
     * @return bool
     */
    public function hasAuction(int $index): bool
    {
        return array_key_exists($index, $this->auctions);
    }

    /**
     * @return array
     */
    public function getBidTokens(): array
    {
        return $this->bidTokens;
    }

    /**
     * @return CurrencyValue[]
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }
}
