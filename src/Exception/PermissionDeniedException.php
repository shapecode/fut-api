<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class PermissionDeniedException extends FutFailedException
{
    protected function getErrorMessage() : string
    {
        return 'Permission denied.';
    }

    protected function getErrorReason() : string
    {
        return 'permission_denied';
    }
}
