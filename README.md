# LocaleBundle

## Installation

To access [locales provider](https://github.com/sokil/LocaleBundle/blob/master/src/Provider/SupportedLocalesProvider.php) from twig template, add global variable in `./app/config/config.yaml`:

```yaml
twig:
    globals:
        locales_provider: "@locale.supported_locales_provider"
```
