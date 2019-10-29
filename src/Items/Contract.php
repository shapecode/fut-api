<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

class Contract extends Item
{
    public function getRating() : ?int
    {
        return $this->get('rating');
    }

    public function getAssetId() : ?int
    {
        return $this->get('assetId');
    }

    public function getCardAssetId() : ?int
    {
        return $this->get('cardassetid');
    }

    public function getWeightRare() : ?int
    {
        return $this->get('weightrare');
    }

    public function getBronze() : ?int
    {
        return $this->get('bronze');
    }

    public function getSilver() : ?int
    {
        return $this->get('silver');
    }

    public function getGold() : ?int
    {
        return $this->get('gold');
    }
}
