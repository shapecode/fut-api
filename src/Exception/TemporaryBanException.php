<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class TemporaryBanException extends PermissionDeniedException
{
    protected function getErrorMessage() : string
    {
        return 'Temporary ban or just too many requests.';
    }

    protected function getErrorReason() : string
    {
        return 'temporary_ban';
    }
}
