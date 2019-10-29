<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class ServerDownException extends FutResponseException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(?ResponseInterface $response = null, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Server down.';
        $reason  = 'server_down';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
