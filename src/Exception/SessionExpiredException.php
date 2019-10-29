<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class SessionExpiredException extends FutResponseException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Account session has expired.';
        $reason  = 'expired_session';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
