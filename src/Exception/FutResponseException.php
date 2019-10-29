<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class FutResponseException extends FutException
{
    /** @var ?string */
    protected $reason;

    /** @var ?ResponseInterface */
    protected $response;

    /**
     * @param mixed[] $options
     */
    public function __construct(
        string $message,
        ?ResponseInterface $response = null,
        ?string $reason = null,
        array $options = [],
        ?Throwable $previous = null
    ) {
        if ($response !== null) {
            $code = $response->getStatusCode();
        } else {
            $code = 0;
        }

        parent::__construct($message, $options, $code, $previous);

        $this->response = $response;
        $this->reason   = $reason;
    }

    public function getReason() : ?string
    {
        return $this->reason;
    }

    public function getResponse() : ?ResponseInterface
    {
        return $this->response;
    }
}
