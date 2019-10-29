<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class ProvideSecurityCodeException extends AuthFailedException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'You must provide a backup code.';
        $reason  = 'security_code';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
