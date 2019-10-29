<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Throwable;

class NoSessionException extends FutException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(?Throwable $previous = null, array $options = [])
    {
        parent::__construct('User is not logged in.', $options, 0, $previous);
    }
}
