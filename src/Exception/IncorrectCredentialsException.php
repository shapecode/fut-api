<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class IncorrectCredentialsException extends AuthFailedException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Your email or password is incorrect.';
        $reason  = 'user_or_pass';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
