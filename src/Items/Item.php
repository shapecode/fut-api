<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

use DateTime;

class Item extends SuperBase implements ItemInterface
{
    public function getItemId() : int
    {
        return $this->get('id');
    }

    public function getResourceId() : int
    {
        return $this->get('resourceId');
    }

    public function isUntradeable() : bool
    {
        return $this->get('untradeable');
    }

    public function getOwners() : int
    {
        return $this->get('owners');
    }

    public function getDiscardValue() : int
    {
        return $this->get('discardValue');
    }

    public function getLastSalePrice() : ?int
    {
        return $this->get('lastSalePrice');
    }

    public function getItemState() : ?string
    {
        return $this->get('itemState');
    }

    public function getCardSubTypeId() : ?int
    {
        return $this->get('cardsubtypeid');
    }

    public function getPile() : ?string
    {
        return $this->get('pile');
    }

    public function getMarketDataMinPrice() : ?int
    {
        return $this->get('marketDataMinPrice');
    }

    public function getMarketDataMaxPrice() : ?int
    {
        return $this->get('marketDataMaxPrice');
    }

    public function getTimestamp() : ?int
    {
        return $this->get('timestamp');
    }

    public function getItemType() : string
    {
        return $this->get('itemType');
    }

    public function getResourceGameYear() : ?int
    {
        return $this->get('resourceGameYear');
    }

    public function getDateTime() : DateTime
    {
        $date = new DateTime();

        if ($this->getTimestamp() !== null) {
            $date->setTimestamp($this->getTimestamp());
        }

        return $date;
    }
}
