<?php

namespace Shapecode\FUT\Client\Config;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Config
 *
 * @package Shapecode\FUT\Client\Config
 * @author  Shapecode
 */
class Config implements ConfigInterface
{

    /** @var string */
    protected $futConfigUrl;

    /** @var array */
    protected $options;

    /**
     * @param array $options
     * @param null  $futConfigUrl
     */
    public function __construct(array $options = [], $futConfigUrl = null)
    {
        if ($futConfigUrl === null) {
            $futConfigUrl = 'https://www.easports.com/fifa/ultimate-team/web-app/config/config.json';
        }

        $this->futConfigUrl = $futConfigUrl;
        $this->resolveOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function isDelay()
    {
        return $this->getOption('delay');
    }

    /**
     * @inheritdoc
     */
    public function getDelayMinTime()
    {
        return $this->getOption('delay_min_time');
    }

    /**
     * @inheritdoc
     */
    public function getDelayMaxTime()
    {
        return $this->getOption('delay_max_time');
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
        return $this->getOption('http_client_options');
    }

    /**
     * @inheritdoc
     */
    public function getHttpClientPlugins()
    {
        return $this->getOption('http_client_plugins');
    }

    /**
     * @inheritdoc
     */
    public function getLogger()
    {
        return $this->getOption('logger');
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getOption($name)
    {
        return $this->options[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * @return OptionsResolver
     */
    protected function getResolver()
    {
        $resolver = new OptionsResolver();

        $url = $this->futConfigUrl;
        $content = file_get_contents($url);
        $futConfig = json_decode($content, true);

        if (!is_array($futConfig)) {
            $futConfig = [];
        }

        $defaults = array_merge($futConfig, [
            'logger'              => new NullLogger(),
            'delay'               => true,
            'delay_min_time'      => 1000,
            'delay_max_time'      => 1500,
            'http_client_options' => [],
            'http_client_plugins' => [],
        ]);

        $resolver->setDefaults($defaults);

        $resolver->setAllowedTypes('logger', [LoggerInterface::class]);

        return $resolver;
    }

    /**
     * @param array $options
     */
    protected function resolveOptions(array $options)
    {
        $this->options = $this->getResolver()->resolve($options);
    }
}
