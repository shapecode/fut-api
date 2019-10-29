<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class ProvideSecurityCodeException extends AuthFailedException
{
    protected function getErrorMessage() : string
    {
        return 'You must provide a backup code.';
    }

    protected function getErrorReason() : string
    {
        return 'security_code';
    }
}
