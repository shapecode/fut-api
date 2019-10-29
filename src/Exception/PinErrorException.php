<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class PinErrorException extends FutFailedException
{
    protected function getErrorMessage() : string
    {
        return 'PinEvent is NOT OK, probably they changed something.';
    }

    protected function getErrorReason() : string
    {
        return 'pin_event';
    }
}
