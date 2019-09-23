<?php

namespace Shapecode\FUT\Client\Items;

/**
 * Class DuplicateItem
 *
 * @package Shapecode\FUT\Client\Items
 * @author  Nikita Loges
 */
class DuplicateItem
{

    /** @var int */
    protected $itemId;

    /** @var int */
    protected $duplicateItemId;

    /**
     * @param int $itemId
     * @param int $duplicateItemId
     */
    public function __construct(int $itemId, int $duplicateItemId)
    {
        $this->itemId = $itemId;
        $this->duplicateItemId = $duplicateItemId;
    }

    /**
     * @return int
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * @return int
     */
    public function getDuplicateItemId(): int
    {
        return $this->duplicateItemId;
    }
}
