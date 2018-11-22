<?php

namespace Shapecode\FUT\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class AuthFailedException
 *
 * @package Shapecode\FUT\Exception
 * @author  Shapecode
 */
class AuthFailedException extends FutResponseException
{

    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Account failed to auth.';
        $reason = 'auth_failed';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
