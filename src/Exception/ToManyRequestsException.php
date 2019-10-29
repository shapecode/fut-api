<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class ToManyRequestsException extends PermissionDeniedException
{
    protected function getErrorMessage() : string
    {
        return 'Too many requests.';
    }

    protected function getErrorReason() : string
    {
        return 'rate_limit_exceeded';
    }
}
