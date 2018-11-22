<?php

namespace Shapecode\FUT\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class TransferMarketDisabledException
 *
 * @package Shapecode\FUT\Exception
 * @author  Shapecode
 */
class TransferMarketDisabledException extends FutResponseException
{

    /**
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     * @param array             $options
     */
    public function __construct(ResponseInterface $response, \Exception $previous = null, $options = [])
    {
        $message = 'Transfer market is probably disabled on this account.';
        $reason = 'market_disabled';

        parent::__construct($message, $response, $reason, $options, $previous);
    }
}
