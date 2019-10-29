<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class MaxSessionsException extends SessionExpiredException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Account is logged in elsewhere.';
        $reason  = 'multiple_sessions';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
