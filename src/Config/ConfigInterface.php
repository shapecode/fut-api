<?php

namespace Shapecode\FUT\Client\Config;

/**
 * Interface ConfigInterface
 *
 * @package Shapecode\FUT\Client\Config
 * @author  Shapecode
 */
interface ConfigInterface
{

    /**
     * @return bool
     */
    public function isDelay();

    /**
     * @return int
     */
    public function getDelayMinTime();

    /**
     * @return int
     */
    public function getDelayMaxTime();

    /**
     * @param int|null $min
     * @param int|null $max
     *
     * @return float|int
     */
    public function getRandomDelayTime($min = null, $max = null);

    /**
     * @return array
     */
    public function getHttpClientOptions();

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getOption($name);

    /**
     * @param $name
     * @param $value
     */
    public function setOption($name, $value);
}
