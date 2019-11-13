<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http\Plugin;

use Http\Client\Common\Plugin;
use Http\Client\Exception;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shapecode\FUT\Client\Http\ClientCall;

class ClientCallPlugin implements Plugin
{
    /** @var ClientCall */
    protected $call;

    public function __construct(ClientCall $call)
    {
        $this->call = $call;
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first) : Promise
    {
        $this->call->setRequest($request);

        return $next($request)->then(function (ResponseInterface $response) {
            $this->call->setResponse($response);

            return $response;
        }, function (Exception $exception) : void {
            if ($exception instanceof Exception\HttpException) {
                $this->call->setResponse($exception->getResponse());
            }

            throw $exception;
        });
    }
}
