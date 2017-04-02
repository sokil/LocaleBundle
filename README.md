# LocaleBundle

## Installation

```
composer.phar require sokil/locale-bundle
```
Configure next parameters in `./app/config/parameters.yml`:

```yaml
    locale: en # default language
    locales: # available languages
        - uk
        - en
    locale_map: # mapping of languages to locales
        uk: uk_UA.utf8
        en: en_US.utf8
```

## Current language resolving

Servise `locale.preferred_language_listener` used to set current language from cookie, request URL or `Accept-Language` request header. It enabled by default, if bundle registered.

## Locales provider

Service `locale.supported_locales_provider` used to get list of all supported languages with localised names of language.

To access [locales provider](https://github.com/sokil/LocaleBundle/blob/master/src/Provider/SupportedLocalesProvider.php) from twig template, add global twig variable in `./app/config/config.yaml`:

```yaml
twig:
    globals:
        locales_provider: "@locale.supported_locales_provider"
```
