<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Locale;

use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class Locale
{
    /** @var Translator */
    protected $translator;

    /** @var mixed[] */
    protected $value;

    public function __construct(string $locale)
    {
        $this->translator = new Translator($locale);

        $path = __DIR__ . '/../Resources/locales/';
        $file = $path . $locale . '.yaml';

        $this->translator->addLoader('yaml', new YamlFileLoader());
        $this->translator->addResource('yaml', $file, $locale);
    }

    /**
     * @param mixed $value
     */
    public function get($value) : string
    {
        return $this->translator->trans($value);
    }
}
