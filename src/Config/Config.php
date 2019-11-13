<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Config;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\OptionsResolver\OptionsResolver;
use const JSON_THROW_ON_ERROR;
use function array_merge;
use function file_get_contents;
use function is_array;
use function json_decode;
use function random_int;

class Config implements ConfigInterface
{
    // phpcs:disable Generic.Files.LineLength.TooLong
    protected const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.70 Safari/537.36';
    // phpcs:enable Generic.Files.LineLength.TooLong

    /** @var string */
    protected $futConfigUrl;

    /** @var mixed[] */
    protected $options;

    /**
     * @param mixed[] $options
     */
    public function __construct(array $options = [], ?string $futConfigUrl = null)
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
    public function isDelay() : bool
    {
        return $this->getOption('delay');
    }

    /**
     * @inheritdoc
     */
    public function getDelayMinTime() : int
    {
        return $this->getOption('delay_min_time');
    }

    /**
     * @inheritdoc
     */
    public function getDelayMaxTime() : int
    {
        return $this->getOption('delay_max_time');
    }

    /**
     * @inheritdoc
     */
    public function getRandomDelayTime(?int $min = null, ?int $max = null) : int
    {
        if ($min === null) {
            $min = $this->getDelayMinTime();
        }

        if ($max === null) {
            $max = $this->getDelayMaxTime();
        }

        $delayMS = random_int($min, $max);

        return $delayMS * 1000;
    }

    /**
     * @inheritdoc
     */
    public function getHttpClientOptions() : array
    {
        return $this->getOption('http_client_options');
    }

    /**
     * @inheritdoc
     */
    public function getHttpClientPlugins() : array
    {
        return $this->getOption('http_client_plugins');
    }

    /**
     * @inheritdoc
     */
    public function getLogger() : LoggerInterface
    {
        return $this->getOption('logger');
    }

    /**
     * @inheritdoc
     */
    public function getUserAgent() : string
    {
        return $this->getOption('user_agent');
    }

    /**
     * @inheritdoc
     */
    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    /**
     * @inheritdoc
     */
    public function setOption(string $name, $value) : void
    {
        $this->options[$name] = $value;
    }

    protected function getResolver() : OptionsResolver
    {
        $resolver = new OptionsResolver();

        $url     = $this->futConfigUrl;
        $content = file_get_contents($url);

        $futConfig = $content !== false? json_decode($content, true, 512, JSON_THROW_ON_ERROR) :null;

        if (! is_array($futConfig)) {
            $futConfig = [];
        }

        $defaults = array_merge($futConfig, [
            'logger'              => new NullLogger(),
            'user_agent'          => self::USER_AGENT,
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
     * @param mixed[] $options
     */
    protected function resolveOptions(array $options) : void
    {
        $this->options = $this->getResolver()->resolve($options);
    }
}
