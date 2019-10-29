<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class IncorrectCredentialsException extends AuthFailedException
{
    protected function getErrorMessage() : string
    {
        return 'Your email or password is incorrect.';
    }

    protected function getErrorReason() : string
    {
        return 'user_or_pass';
    }
}
