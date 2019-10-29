<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class SessionExpiredException extends FutFailedException
{
    protected function getErrorMessage() : string
    {
        return 'Account session has expired.';
    }

    protected function getErrorReason() : string
    {
        return 'expired_session';
    }
}
