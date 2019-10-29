<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class NoPersonaException extends AuthFailedException
{
    protected function getErrorMessage() : string
    {
        return 'Error during login process (no persona found).';
    }

    protected function getErrorReason() : string
    {
        return 'no_club';
    }
}
