<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

use ArrayAccess;
use function array_key_exists;

abstract class SuperBase implements ArrayAccess
{
    /** @var mixed[] */
    protected $data;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed[]
     */
    public function getArray() : array
    {
        return $this->data;
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->data[$offset]) || array_key_exists($offset, $this->data);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value) : void
    {
        $this->data[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset) : void
    {
        if (! $this->offsetExists($offset)) {
            return;
        }

        unset($this->data[$offset]);
    }
}
