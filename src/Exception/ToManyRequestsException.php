<?php

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class ToManyRequestsException
 *
 * @package Shapecode\FUT\Client\Exception
 * @author  Shapecode
 */
class ToManyRequestsException extends PermissionDeniedException
{

    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Too many requests.';
        $reason = 'rate_limit_exceeded';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
