<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class TemporaryBanException extends PermissionDeniedException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Temporary ban or just too many requests.';
        $reason  = 'temporary_ban';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
