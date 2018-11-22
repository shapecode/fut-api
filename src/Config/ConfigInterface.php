<?php

namespace Shapecode\FUT\Config;

use Http\Client\Common\Plugin;

/**
 * Interface ConfigInterface
 *
 * @package Shapecode\FUT\Config
 * @author  Shapecode
 */
interface ConfigInterface
{

    /**
     * @param $key
     * @param $value
     */
    public function setValue($key, $value);

    /**
     * @return bool
     */
    public function isDelay();

    /**
     * @param bool $delay
     */
    public function setDelay($delay);

    /**
     * @return int
     */
    public function getDelayMinTime();

    /**
     * @param int $delayMinTime
     */
    public function setDelayMinTime($delayMinTime);

    /**
     * @return int
     */
    public function getDelayMaxTime();

    /**
     * @param int $delayMaxTime
     */
    public function setDelayMaxTime($delayMaxTime);

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
     * @param array $httpClientOptions
     */
    public function setHttpClientOptions(array $httpClientOptions);

    /**
     * @return Plugin[]
     */
    public function getHttpClientPlugins();

    /**
     * @param Plugin[] $httpClientPlugins
     */
    public function setHttpClientPlugins(array $httpClientPlugins);
}
