<?php

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class PermissionDeniedException
 *
 * @package Shapecode\FUT\Client\Exception
 * @author  Shapecode
 */
class PermissionDeniedException extends FutResponseException
{

    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Permission denied.';
        $reason = 'permission_denied';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
