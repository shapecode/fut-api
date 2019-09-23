<?php

namespace Shapecode\FUT\Client\Items;

/**
 * Class Health
 *
 * @package Shapecode\FUT\Client\Items
 * @author  Nikita Loges
 */
class Health extends Item
{

    public function getAssetId(): int
    {
        return $this->get('assetId');
    }

    public function getRating()
    {
        return $this->get('rating');
    }

    public function getCardAssetId()
    {
        return $this->get('cardassetid');
    }

    public function getWeightRare()
    {
        return $this->get('weightrare');
    }

    public function getAmount()
    {
        return $this->get('amount');
    }

}
