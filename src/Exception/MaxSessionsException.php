<?php

namespace Shapecode\FUT\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class MaxSessionsException
 *
 * @package Shapecode\FUT\Exception
 * @author  Shapecode
 */
class MaxSessionsException extends SessionExpiredException
{

    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Account is logged in elsewhere.';
        $reason = 'multiple_sessions';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
