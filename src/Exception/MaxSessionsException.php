<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class MaxSessionsException extends SessionExpiredException
{
    protected function getErrorMessage() : string
    {
        return 'Account is logged in elsewhere.';
    }

    protected function getErrorReason() : string
    {
        return 'multiple_sessions';
    }
}
