<?php

namespace Shapecode\FUT\Client\Items;

/**
 * Class Health
 *
 * @package Shapecode\FUT\Client\Items
 * @author  Nikita Loges
 */
class Kit extends Item
{

    public function getAssetId(): int
    {
        return $this->get('assetId');
    }

    public function getRating()
    {
        return $this->get('rating');
    }

    public function getCategory()
    {
        return $this->get('category');
    }

    public function getName()
    {
        return $this->get('name');
    }

}
