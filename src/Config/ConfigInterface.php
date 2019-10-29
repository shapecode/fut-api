<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Config;

use Psr\Log\LoggerInterface;

/**
 * Interface ConfigInterface
 */
interface ConfigInterface
{
    public function isDelay() : bool;

    public function getDelayMinTime() : int;

    public function getDelayMaxTime() : int;

    public function getRandomDelayTime(?int $min = null, ?int $max = null) : int;

    /**
     * @return mixed[]
     */
    public function getHttpClientOptions() : array;

    public function getLogger() : LoggerInterface;

    public function getUserAgent() : string;

    /**
     * @return mixed
     */
    public function getOption(string $name);

    /**
     * @param mixed $value
     */
    public function setOption(string $name, $value) : void;
}
