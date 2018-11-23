<?php

namespace Shapecode\FUT\Client\Config;

use Http\Client\Common\Plugin;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class Config
 *
 * @package Shapecode\FUT\Client\Config
 * @author  Shapecode
 */
class Config implements ConfigInterface
{

    /** @var bool */
    protected $delay = true;

    /** @var int */
    protected $delayMinTime = 1000;

    /** @var int */
    protected $delayMaxTime = 1500;

    /** @var array */
    protected $httpClientOptions = [];

    /** @var Plugin[] */
    protected $httpClientPlugins = [];

    /** @var PropertyAccessor */
    protected $pa;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->pa = PropertyAccess::createPropertyAccessor();

        if (isset($options['pa'])) {
            unset($options['pa']);
        }

        foreach ($options as $key => $value) {
            $this->setValue($key, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function setValue($key, $value)
    {
        if ($key === 'pa') {
            return;
        }

        if ($this->pa->isWritable($this, $key)) {
            $this->pa->setValue($this, $key, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function isDelay()
    {
        return $this->delay;
    }

    /**
     * @inheritdoc
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
    }

    /**
     * @inheritdoc
     */
    public function getDelayMinTime()
    {
        return $this->delayMinTime;
    }

    /**
     * @inheritdoc
     */
    public function setDelayMinTime($delayMinTime)
    {
        $this->delayMinTime = $delayMinTime;
    }

    /**
     * @inheritdoc
     */
    public function getDelayMaxTime()
    {
        return $this->delayMaxTime;
    }

    /**
     * @inheritdoc
     */
    public function setDelayMaxTime($delayMaxTime)
    {
        $this->delayMaxTime = $delayMaxTime;
    }

    /**
     * @inheritdoc
     */
    public function getRandomDelayTime($min = null, $max = null)
    {
        if ($min === null) {
            $min = $this->getDelayMinTime();
        }

        if ($max === null) {
            $max = $this->getDelayMaxTime();
        }

        $delayMS = mt_rand($min, $max);

        return $delayMS * 1000;
    }

    /**
     * @inheritdoc
     */
    public function getHttpClientOptions()
    {
        return $this->httpClientOptions;
    }

    /**
     * @inheritdoc
     */
    public function setHttpClientOptions(array $httpClientOptions)
    {
        $this->httpClientOptions = $httpClientOptions;
    }

    /**
     * @inheritdoc
     */
    public function getHttpClientPlugins()
    {
        return $this->httpClientPlugins;
    }

    /**
     * @inheritdoc
     */
    public function setHttpClientPlugins(array $httpClientPlugins)
    {
        $this->httpClientPlugins = $httpClientPlugins;
    }
}
