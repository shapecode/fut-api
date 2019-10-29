<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

use DateTime;

/**
 * Interface TradeItemInterface
 */
interface TradeItemInterface
{
    public function getTradeId() : int;

    public function getItem() : ItemInterface;

    public function getTradeState() : ?string;

    public function getBuyNowPrice() : int;

    public function getBidValue() : int;

    public function getCurrentBid() : int;

    public function getOffers() : int;

    public function isWatched() : bool;

    public function getBidState() : ?string;

    public function getStartingBid() : int;

    public function getConfidenceValue() : int;

    public function getExpires() : ?int;

    public function getExpireDate() : ?DateTime;

    public function getSellerName() : ?string;

    public function getSellerEstablished() : int;

    public function getSellerId() : int;

    public function isTradeOwner() : ?bool;
}
