<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Config;

interface ConfigInterface
{
    public function getUserAgent(): string;

    /**
     * @return mixed[]
     */
    public function getOptions(): array;

    /**
     * @return mixed
     */
    public function getOption(string $name);

    /**
     * @param mixed $value
     */
    public function setOption(string $name, $value): void;
}
