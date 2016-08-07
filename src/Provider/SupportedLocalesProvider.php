<?php

namespace Sokil\LocaleBundle\Provider;

use Sokil\IsoCodes\Languages;

class SupportedLocalesProvider
{
    private $locales;

    private $languageDictionary;

    public function __construct(
        array $locales,
        Languages $languages
    ) {
        $this->locales = $locales;

        $this->languageDictionary = $languages;
    }

    public function getLanguages()
    {
        return array_map([$this->languageDictionary, 'getByAlpha2'], $this->locales);
    }
}