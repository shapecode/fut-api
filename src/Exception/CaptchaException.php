<?php

namespace Shapecode\FUT\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class CaptchaException
 *
 * @package Shapecode\FUT\Exception
 * @author  Shapecode
 */
class CaptchaException extends FutResponseException
{

    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Account has received a captcha';
        $reason = 'captcha';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
