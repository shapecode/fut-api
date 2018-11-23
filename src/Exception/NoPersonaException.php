<?php

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class NoPersonaException
 *
 * @package Shapecode\FUT\Client\Exception
 * @author  Shapecode
 */
class NoPersonaException extends AuthFailedException
{
    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Error during login process (no persona found).';
        $reason = 'no_club';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }

}
