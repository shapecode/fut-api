<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Config;

use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_merge;
use function file_get_contents;
use function is_array;
use function json_decode;

use const JSON_THROW_ON_ERROR;

class Config implements ConfigInterface
{
    // phpcs:disable Generic.Files.LineLength.TooLong
    protected const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.70 Safari/537.36';

    // phpcs:enable Generic.Files.LineLength.TooLong

    protected string $futConfigUrl;

    /** @var mixed[] */
    protected array $options;

    /**
     * @param mixed[] $options
     */
    public function __construct(
        array $options = [],
        ?string $futConfigUrl = null
    ) {
        if ($futConfigUrl === null) {
            $futConfigUrl = 'https://www.ea.com/fifa/ultimate-team/web-app/config/config.json';
        }

        $this->futConfigUrl = $futConfigUrl;
        $this->resolveOptions($options);
    }

    public function getUserAgent(): string
    {
        return $this->getOption('userAgent');
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    /**
     * @inheritDoc
     */
    public function setOption(string $name, $value): void
    {
        $this->options[$name] = $value;
    }

    protected function getResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();

        $url     = $this->futConfigUrl;
        $content = file_get_contents($url);

        $futConfig = $content !== false ? json_decode($content, true, 512, JSON_THROW_ON_ERROR) : null;

        if (! is_array($futConfig)) {
            $futConfig = [];
        }

        $defaults = array_merge($futConfig, [
            'userAgent'         => self::USER_AGENT,
        ]);

        $resolver->setDefaults($defaults);

        return $resolver;
    }

    /**
     * @param mixed[] $options
     */
    protected function resolveOptions(array $options): void
    {
        $this->options = $this->getResolver()->resolve($options);
    }
}
