<?php

namespace Shapecode\FUT\Client\Items;

/**
 * Class Contract
 *
 * @package Shapecode\FUT\Client\Items
 * @author  Nikita Loges
 */
class Contract extends Item
{

    public function getRating()
    {
        return $this->get('rating');
    }

    public function getAssetId(): int
    {
        return $this->get('assetId');
    }

    public function getCardAssetId()
    {
        return $this->get('cardassetid');
    }

    public function getWeightRare()
    {
        return $this->get('weightrare');
    }

    public function getBronze()
    {
        return $this->get('bronze');
    }

    public function getSilver()
    {
        return $this->get('silver');
    }

    public function getGold()
    {
        return $this->get('gold');
    }
}
