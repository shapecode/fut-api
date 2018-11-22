<?php

namespace Shapecode\FUT\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class UserExpiredException
 *
 * @package Shapecode\FUT\Exception
 * @author  Shapecode
 */
class UserExpiredException extends AuthFailedException
{
    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Appears your access has expired.';
        $reason = 'user_expired';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }

}
