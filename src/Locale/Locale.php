<?php

namespace Shapecode\FUT\Client\Locale;

use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

/**
 * Class Locale
 *
 * @package Shapecode\FUT\Client\Locale
 * @author  Shapecode
 */
class Locale
{

    /** @var Translator */
    protected $translator;

    /** @var array */
    protected $value;

    /**
     * @param string $locale
     */
    public function __construct($locale)
    {
        $this->translator = new Translator($locale);

        $path = __DIR__ . '/../Resources/locales/';
        $file = $path . $locale . '.yaml';

        $this->translator->addLoader('yaml', new YamlFileLoader());
        $this->translator->addResource('yaml', $file, $locale);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function get($value)
    {
        return $this->translator->trans($value);
    }
}
