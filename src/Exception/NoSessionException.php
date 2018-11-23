<?php

namespace Shapecode\FUT\Client\Exception;

/**
 * Class NoSessionException
 *
 * @package Shapecode\FUT\Client\Exception
 * @author  Shapecode
 */
class NoSessionException extends FutException
{

    /**
     * @param \Exception|null $previous
     * @param array           $options
     */
    public function __construct(\Exception $previous = null, $options = [])
    {
        parent::__construct('User is not logged in.', $options, 0, $previous);
    }
}
