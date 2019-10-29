<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class UserExpiredException extends AuthFailedException
{
    protected function getErrorMessage() : string
    {
        return 'Appears your access has expired.';
    }

    protected function getErrorReason() : string
    {
        return 'user_expired';
    }
}
