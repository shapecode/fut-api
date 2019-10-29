<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class UserExpiredException extends AuthFailedException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Appears your access has expired.';
        $reason  = 'user_expired';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
