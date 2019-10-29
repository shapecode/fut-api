<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

class CurrencyValue extends SuperBase
{
    public function getName() : string
    {
        return $this->get('name');
    }

    public function getFunds() : int
    {
        return $this->get('funds');
    }

    public function getFinalFunds() : int
    {
        return $this->get('finalFunds');
    }
}
