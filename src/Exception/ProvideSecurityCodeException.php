<?php

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class UserExpiredException
 *
 * @package Shapecode\FUT\Client\Exception
 * @author  Shapecode
 */
class ProvideSecurityCodeException extends AuthFailedException
{
    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'You must provide a backup code.';
        $reason = 'security_code';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }

}
