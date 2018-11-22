<?php

namespace Shapecode\FUT\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class FutException
 *
 * @package Shapecode\FUT\Exception
 * @author  Shapecode
 */
class FutResponseException extends FutException
{

    /** @var string */
    protected $reason;

    /** @var ResponseInterface */
    protected $response;

    /**
     * @param                   $message
     * @param ResponseInterface $response
     * @param null              $reason
     * @param array             $options
     * @param \Exception|null   $previous
     */
    public function __construct($message, ResponseInterface $response = null, $reason = null, $options = [], \Exception $previous = null)
    {
        if ($response) {
            $code = $response->getStatusCode();
        } else {
            $code = 0;
        }

        parent::__construct($message, $options, $code, $previous);

        $this->response = $response;
        $this->reason = $reason;
    }

    /**
     * @return null|string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
