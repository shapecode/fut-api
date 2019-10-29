<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

class Kit extends Item
{
    public function getAssetId() : ?int
    {
        return $this->get('assetId');
    }

    public function getRating() : ?int
    {
        return $this->get('rating');
    }

    public function getCategory() : ?string
    {
        return $this->get('category');
    }

    public function getName() : ?string
    {
        return $this->get('name');
    }
}
