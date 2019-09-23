<?php

namespace Shapecode\FUT\Client\Items;

/**
 * Interface ItemInterface
 *
 * @package Shapecode\FUT\Client\Items
 * @author  Nikita Loges
 */
interface ItemInterface
{

    public function getItemId(): int;

    public function getResourceId(): int;

    public function isUntradeable(): bool;

    public function getOwners(): int;

    public function getDiscardValue(): int;

    public function getLastSalePrice(): ?int;

    public function getMarketDataMinPrice(): ?int;

    public function getMarketDataMaxPrice(): ?int;

    public function getItemType(): string;

    public function getTimestamp(): ?int;
}
