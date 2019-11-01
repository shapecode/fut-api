<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\CurrencyValue;
use Shapecode\FUT\Client\Items\TradeItemInterface;
use function array_key_exists;
use function count;

class TradeStatusResponse
{
    /** @var int|null */
    private $credits;

    /** @var TradeItemInterface[] */
    private $auctions;

    /** @var mixed[] */
    private $bidTokens;

    /** @var CurrencyValue[] */
    private $currencies;

    /**
     * @param TradeItemInterface[] $auctions
     * @param mixed[]              $bidTokens
     * @param CurrencyValue[]      $currencies
     */
    public function __construct(?int $credits, array $auctions, array $bidTokens, array $currencies)
    {
        $this->credits    = $credits;
        $this->auctions   = $auctions;
        $this->bidTokens  = $bidTokens;
        $this->currencies = $currencies;
    }

    public function getCredits() : ?int
    {
        return $this->credits;
    }

    /**
     * @return TradeItemInterface[]
     */
    public function getAuctions() : array
    {
        return $this->auctions;
    }

    public function hasAuctions() : bool
    {
        return count($this->auctions) > 0;
    }

    public function getAuction(int $index) : ?TradeItemInterface
    {
        if (! $this->hasAuction($index)) {
            return null;
        }

        return $this->auctions[$index];
    }

    public function hasAuction(int $index) : bool
    {
        return array_key_exists($index, $this->auctions);
    }

    /**
     * @return mixed[]
     */
    public function getBidTokens() : array
    {
        return $this->bidTokens;
    }

    /**
     * @return CurrencyValue[]
     */
    public function getCurrencies() : array
    {
        return $this->currencies;
    }
}
