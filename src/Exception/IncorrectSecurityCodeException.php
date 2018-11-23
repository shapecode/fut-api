<?php

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class IncorrectSecurityCodeException
 *
 * @package Shapecode\FUT\Client\Exception
 * @author  Shapecode
 */
class IncorrectSecurityCodeException extends ProvideSecurityCodeException
{
    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'You provided an incorrect backup code.';
        $reason = 'security_code';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }

}
