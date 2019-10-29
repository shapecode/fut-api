<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Exception;
use Throwable;

class FutException extends Exception
{
    /** @var mixed[] */
    protected $options;

    /**
     * @param mixed[] $options
     */
    public function __construct(string $message, array $options = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->options = $options;
    }

    /**
     * @return mixed[]
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * @return mixed|null
     */
    public function getOption(string $name)
    {
        return $this->options[$name] ?? null;
    }
}
