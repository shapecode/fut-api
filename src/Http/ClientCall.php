<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClientCall
{
    /** @var RequestInterface */
    protected $request;

    /** @var ResponseInterface */
    protected $response;

    /** @var mixed */
    protected $contents;

    public function getRequest() : RequestInterface
    {
        return $this->request;
    }

    public function setRequest(RequestInterface $request) : void
    {
        $this->request = $request;
    }

    public function getResponse() : ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response) : void
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
