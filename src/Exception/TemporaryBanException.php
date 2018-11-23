<?php

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class TemporaryBanException
 *
 * @package Shapecode\FUT\Client\Exception
 * @author  Shapecode
 */
class TemporaryBanException extends PermissionDeniedException
{

    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Temporary ban or just too many requests.';
        $reason = 'temporary_ban';

        FutResponseException::__construct($message, $response, $reason, $options, $previous);
    }
}
