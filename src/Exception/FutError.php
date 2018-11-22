<?php

namespace Shapecode\FUT\Exception;

/**
 * Class FutError
 *
 * @package Shapecode\FUT\Exception
 * @author  Shapecode
 *
 * @deprecated
 */
class FutError extends \Exception
{

    /** @var array */
    protected $options;

    /**
     * @param                 $message
     * @param array           $options
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $options = [], $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function getOption($name)
    {
        return $this->options[$name] ?? null;
    }

}
