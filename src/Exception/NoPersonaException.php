<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class NoPersonaException extends AuthFailedException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Error during login process (no persona found).';
        $reason  = 'no_club';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
