<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class IncorrectSecurityCodeException extends ProvideSecurityCodeException
{
    protected function getErrorMessage() : string
    {
        return 'You provided an incorrect backup code.';
    }

    protected function getErrorReason() : string
    {
        return 'security_code';
    }
}
