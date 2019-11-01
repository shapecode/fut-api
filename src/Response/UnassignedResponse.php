<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\DuplicateItem;
use Shapecode\FUT\Client\Items\ItemInterface;

class UnassignedResponse
{
    /** @var ItemInterface[] */
    private $items;

    /** @var DuplicateItem[] */
    private $duplicateItemIdList;

    /**
     * @param ItemInterface[] $items
     * @param DuplicateItem[] $duplicateItemIdList
     */
    public function __construct(array $items, array $duplicateItemIdList)
    {
        $this->items               = $items;
        $this->duplicateItemIdList = $duplicateItemIdList;
    }

    /**
     * @return ItemInterface[]
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * @return DuplicateItem[]
     */
    public function getDuplicateItemIdList() : array
    {
        return $this->duplicateItemIdList;
    }
}
