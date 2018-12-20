<?php

namespace Shapecode\FUT\Client\Items;

/**
 * Class Item
 *
 * @package Shapecode\FUT\Client\Items
 * @author  Nikita Loges
 */
class Item extends SuperBase
{

    public function getId()
    {
        return $this->get('id');
    }

    public function getTimestamp()
    {
        return $this->get('timestamp');
    }

    public function getDateTime()
    {
        $date = new \DateTime();
        $date->setTimestamp($this->getTimestamp());

        return $date;
    }
}
