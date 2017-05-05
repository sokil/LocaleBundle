<?php

namespace Sokil\LocaleBundle\Provider;

use Sokil\IsoCodes\Languages;

class SupportedLocalesProvider
{
    /**
     * List of language to locale relations (uk => uk_UA.UTF-8, en => en_GB.UTF-8, ...)
     * @var array
     */
    private $supportedLocales;

    /**
     * @var Languages
     */
    private $languageDictionary;

    public function __construct(
        array $locales,
        Languages $languages
    ) {
        $this->supportedLocales = $locales;
        $this->languageDictionary = $languages;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return array_map(
            [$this->languageDictionary, 'getByAlpha2'],
            array_keys($this->supportedLocales)
        );
    }
}