<?php

namespace Sokil\LocaleBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Initializes the language based on the current request.
 */
class PreferredLanguageListener implements EventSubscriberInterface
{
    private $defaultLocale;
    private $supportedLocales = [];
    private $localeMap = [];
    private $translator;

    public function __construct(
        $defaultLocale,
        array $supportedLocales,
        array $localeMap,
        TranslatorInterface $translator
    ) {
        $this->defaultLocale = $defaultLocale;
        $this->supportedLocales = $supportedLocales;
        $this->localeMap = $localeMap;

        $this->translator = $translator;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        
        $request->setDefaultLocale($this->defaultLocale);

        $locale = $this->detectLocale($request);

        // define locale
        $request->setLocale($locale);

        // define locale
        $getTextLocales = $this->localeMap[$locale];
        putenv('LANGUAGE=' . $getTextLocales);
        putenv('LC_ALL=' . $getTextLocales);
        setlocale(LC_ALL, $getTextLocales);

        // define translator locale
        $this->translator->setLocale($locale);
    }

    private function detectLocale(Request $request)
    {
        // get from cookie
        if ($request->cookies->has('lang')) {
            $locale = $request->cookies->get('lang');
            if (in_array($locale, $this->supportedLocales)) {
                return $locale;
            }
        }

        // get from path
        $requestUri = $request->getRequestUri();
        $uriParts = explode('/', $requestUri);
        $locale = !empty($uriParts[1]) ? $uriParts[1] : null;
        if ($locale && in_array($locale, $this->supportedLocales)) {
            return $locale;
        }

        // get from header
        return $request->getPreferredLanguage($this->supportedLocales);
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the Router to have access to the _locale
            KernelEvents::REQUEST => array(array('onKernelRequest', 16)),
        );
    }
}
