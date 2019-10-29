<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class TransferMarketDisabledException extends FutResponseException
{
    /**
     * @param mixed[] $options
     */
    public function __construct(ResponseInterface $response, ?Throwable $previous = null, array $options = [])
    {
        $message = 'Transfer market is probably disabled on this account.';
        $reason  = 'market_disabled';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
