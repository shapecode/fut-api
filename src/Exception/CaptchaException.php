<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class CaptchaException extends FutResponseException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Account has received a captcha';
        $reason  = 'captcha';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
