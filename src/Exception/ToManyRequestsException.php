<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class ToManyRequestsException extends PermissionDeniedException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Too many requests.';
        $reason  = 'rate_limit_exceeded';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
