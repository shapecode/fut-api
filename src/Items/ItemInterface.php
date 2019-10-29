<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

/**
 * Interface ItemInterface
 */
interface ItemInterface
{
    public function getItemId() : int;

    public function getResourceId() : int;

    public function isUntradeable() : bool;

    public function getOwners() : int;

    public function getDiscardValue() : int;

    public function getLastSalePrice() : ?int;

    public function getMarketDataMinPrice() : ?int;

    public function getMarketDataMaxPrice() : ?int;

    public function getItemType() : string;

    public function getTimestamp() : ?int;
}
