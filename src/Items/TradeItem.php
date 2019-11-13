<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

use Carbon\Carbon;
use DateTime;

class TradeItem extends SuperBase implements TradeItemInterface
{
    /** @var Item */
    protected $item;

    /**
     * @inheritDoc
     */
    public function __construct(array $data, Item $item)
    {
        parent::__construct($data);

        $this->item = $item;
    }

    public function getTradeId() : int
    {
        return $this->get('tradeId');
    }

    public function getItem() : ItemInterface
    {
        return $this->item;
    }

    public function getTradeState() : ?string
    {
        return $this->get('tradeState');
    }

    public function getBuyNowPrice() : int
    {
        return $this->get('buyNowPrice');
    }

    public function getBidValue() : int
    {
        return $this->getCurrentBid() > 200 ? $this->getCurrentBid() : $this->getStartingBid();
    }

    public function getCurrentBid() : int
    {
        return $this->get('currentBid');
    }

    public function getOffers() : int
    {
        return $this->get('offers');
    }

    public function isWatched() : bool
    {
        return $this->get('watched');
    }

    public function getBidState() : ?string
    {
        return $this->get('bidState');
    }

    public function getStartingBid() : int
    {
        return $this->get('startingBid');
    }

    public function getConfidenceValue() : int
    {
        return $this->get('confidenceValue');
    }

    public function getExpires() : int
    {
        return $this->get('expires');
    }

    public function getExpireDate() : ?DateTime
    {
        if ($this->getExpires() > 0) {
            return new Carbon('+' . $this->getExpires() . ' seconds');
        }

        return null;
    }

    public function getSellerName() : ?string
    {
        return $this->get('sellerName');
    }

    public function getSellerEstablished() : int
    {
        return $this->get('sellerEstablished');
    }

    public function getSellerId() : int
    {
        return $this->get('sellerId');
    }

    public function isTradeOwner() : ?bool
    {
        return $this->get('tradeOwner');
    }
}
