<?php

namespace Shapecode\FUT\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class ServerDownException
 *
 * @package Shapecode\FUT\Exception
 * @author  Shapecode
 */
class ServerDownException extends FutResponseException
{

    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response = null, \Exception $previous = null, $options = [])
    {
        $message = 'Server down.';
        $reason = 'server_down';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
