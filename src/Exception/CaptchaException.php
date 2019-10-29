<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

class CaptchaException extends FutFailedException
{
    protected function getErrorMessage() : string
    {
        return 'Account has received a captcha';
    }

    protected function getErrorReason() : string
    {
        return 'captcha';
    }
}
