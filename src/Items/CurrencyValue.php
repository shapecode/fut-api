<?php

namespace Shapecode\FUT\Client\Items;

/**
 * Class CurrencyValue
 *
 * @package Shapecode\FUT\Client\Items
 * @author  Nikita Loges
 */
class CurrencyValue extends SuperBase
{

    public function getName(): string
    {
        return $this->get('name');
    }

    public function getFunds(): int
    {
        return $this->get('funds');
    }

    public function getFinalFunds(): int
    {
        return $this->get('finalFunds');
    }
}
