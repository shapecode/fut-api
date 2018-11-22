<?php

namespace Shapecode\FUT\Exception;

/**
 * Class NoSessionException
 *
 * @package Shapecode\FUT\Exception
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
