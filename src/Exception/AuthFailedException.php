<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class AuthFailedException extends FutFailedException
{
    protected function getErrorMessage() : string
    {
        return 'Account failed to auth.';
    }

    protected function getErrorReason() : string
    {
        return 'auth_failed';
    }
}
