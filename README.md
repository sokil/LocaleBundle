# LocaleBundle

## Installation

```
composer.phar require sokil/locale-bundle
```
Configure next parameters in `./app/config/config.yml`:

```yaml
locale:
    query_parameter: lang
    cookie_parameter: lang
    path_parameter: false
    locales: # mapping of languages to locales
        uk: uk_UA.utf8
        en: en_US.utf8
```

## Current language resolving

Servise `locale.preferred_language_listener` used to set current language from cookie, request URL or `Accept-Language` request header. It enabled by default, if bundle registered.

## Switch languages

Action `setAction` from controller `Sokil\LocaleBundle\Controller\LangController` used to set current lang.

## Locales provider

Service `locale.supported_locales_provider` used to get list of all supported languages with localised names of language.

To access [locales provider](https://github.com/sokil/LocaleBundle/blob/master/src/Provider/SupportedLocalesProvider.php) from twig template, add global twig variable in `./app/config/config.yaml`:

```yaml
twig:
    globals:
        locales_provider: "@locale.supported_locales_provider"
```
