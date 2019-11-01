<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

class Settings
{
    /** @var Config[] */
    private $configs;

    /**
     * @param Config[] $configs
     */
    public function __construct(array $configs)
    {
        $this->configs = $configs;
    }

    /**
     * @return Config[]
     */
    public function getConfigs() : array
    {
        return $this->configs;
    }
}
