<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class TransferMarketDisabledException extends FutFailedException
{
    protected function getErrorMessage() : string
    {
        return 'Transfer market is probably disabled on this account.';
    }

    protected function getErrorReason() : string
    {
        return 'market_disabled';
    }
}
