<?php

namespace Shapecode\FUT\Client\Items;

/**
 * Interface TradeItemInterface
 *
 * @package Shapecode\FUT\Client\Items
 * @author  Nikita Loges
 */
interface TradeItemInterface
{


    public function getTradeId(): int;

    public function getItem(): ItemInterface;

    public function getTradeState(): ?string;

    public function getBuyNowPrice(): int;

    public function getBidValue(): int;

    public function getCurrentBid(): int;

    public function getOffers(): int;

    public function isWatched(): bool;

    public function getBidState(): ?string;

    public function getStartingBid(): int;

    public function getConfidenceValue(): int;

    public function getExpires(): ?int;

    /**
     * @return \DateTime|null
     */
    public function getExpireDate(): ?\DateTime;

    public function getSellerName(): ?string;

    public function getSellerEstablished(): int;

    public function getSellerId(): int;

    public function isTradeOwner(): ?bool;
}
