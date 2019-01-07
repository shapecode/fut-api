<?php

namespace Shapecode\FUT\Client\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ClientCall
 *
 * @package Shapecode\FUT\Client\Http
 * @author  Nikita Loges
 */
class ClientCall
{

    /** @var RequestInterface */
    protected $request;

    /** @var ResponseInterface */
    protected $response;

    /** @var mixed */
    protected $contents;

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        if ($this->contents === null) {
            $this->contents = $this->getResponse()->getBody()->getContents();
        }

        return $this->contents;
    }
}
