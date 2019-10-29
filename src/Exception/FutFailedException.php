<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

abstract class FutFailedException extends FutResponseException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(?ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        parent::__construct($this->getErrorMessage(), $response, $this->getErrorReason(), $options, $previous);
    }

    abstract protected function getErrorMessage() : string;

    abstract protected function getErrorReason() : string;
}
