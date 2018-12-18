<?php

namespace Shapecode\FUT\Client\Http\Plugin;

use Http\Client\Common\Plugin;
use Http\Client\Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shapecode\FUT\Client\Http\ClientCall;

/**
 * Class ClientCallPlugin
 *
 * @package Shapecode\FUT\Client\Http\Plugin
 * @author  Nikita Loges
 */
class ClientCallPlugin implements Plugin
{

    protected $call;

    /**
     * @param ClientCall $call
     */
    public function __construct(ClientCall $call)
    {
        $this->call = $call;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $this->call->setRequest($request);

        return $next($request)->then(function (ResponseInterface $response) {
            $this->call->setResponse($response);

            return $response;
        }, function (Exception $exception) {
            if ($exception instanceof Exception\HttpException) {
                $this->call->setResponse($exception->getResponse());
            }

            throw $exception;
        });
    }
}
