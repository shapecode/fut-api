<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Config;

interface ConfigInterface
{
    public function isDelay() : bool;

    public function getDelayMinTime() : int;

    public function getDelayMaxTime() : int;

    public function getRandomDelayTime(?int $min = null, ?int $max = null) : int;

    public function getUserAgent() : string;

    /**
     * @return mixed[]
     */
    public function getOptions() : array;

    /**
     * @return mixed
     */
    public function getOption(string $name);

    /**
     * @param mixed $value
     */
    public function setOption(string $name, $value) : void;
}
