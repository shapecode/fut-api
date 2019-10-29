<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class ServerDownException extends FutFailedException
{
    protected function getErrorMessage() : string
    {
        return 'Server down.';
    }

    protected function getErrorReason() : string
    {
        return 'server_down';
    }
}
