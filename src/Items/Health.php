<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

class Health extends Item
{
    public function getAssetId() : ?int
    {
        return $this->get('assetId');
    }

    public function getRating() : ?int
    {
        return $this->get('rating');
    }

    public function getCardAssetId() : ?int
    {
        return $this->get('cardassetid');
    }

    public function getWeightRare() : ?int
    {
        return $this->get('weightrare');
    }

    public function getAmount() : ?int
    {
        return $this->get('amount');
    }
}
