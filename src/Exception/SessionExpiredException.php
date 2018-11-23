<?php

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class SessionExpiredException
 *
 * @package Shapecode\FUT\Client\Exception
 * @author  Shapecode
 */
class SessionExpiredException extends FutResponseException
{

    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Account session has expired.';
        $reason = 'expired_session';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
