<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

class DuplicateItem
{
    /** @var int */
    protected $itemId;

    /** @var int */
    protected $duplicateItemId;

    public function __construct(int $itemId, int $duplicateItemId)
    {
        $this->itemId          = $itemId;
        $this->duplicateItemId = $duplicateItemId;
    }

    public function getItemId() : int
    {
        return $this->itemId;
    }

    public function getDuplicateItemId() : int
    {
        return $this->duplicateItemId;
    }
}
