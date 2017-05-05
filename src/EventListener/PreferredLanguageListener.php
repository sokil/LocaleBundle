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
    private $translator;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * List of language to locale relations (uk => uk_UA.UTF-8, en => en_GB.UTF-8, ...)
     * @var array
     */
    private $supportedLocales = [];

    /**
     * Query parameter used to define language
     * @var string
     */
    private $queryParameter = null;

    /**
     * Cookie parameter used to define language
     * @var string
     */
    private $cookieParameter = null;


    /**
     * Define locale from first token in path
     * @var bool
     */
    private $pathParameter = false;

    /**
     * @param TranslatorInterface $translator
     * @param string $defaultLocale
     * @param array $supportedLocales
     * @param string $queryParameter
     * @param string $cookieParameter
     * @param bool $pathParameter
     */
    public function __construct(
        TranslatorInterface $translator,
        $defaultLocale,
        array $supportedLocales,
        $queryParameter = null,
        $cookieParameter = null,
        $pathParameter = null
    ) {
        $this->defaultLocale = $defaultLocale;
        $this->supportedLocales = $supportedLocales;
        $this->translator = $translator;
        $this->queryParameter = $queryParameter;
        $this->cookieParameter = $cookieParameter;
        $this->pathParameter = $pathParameter;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        
        $request->setDefaultLocale($this->defaultLocale);

        // get locale and language
        $language = $this->detectLanguage($request);
        $locale = $this->supportedLocales[$language];

        // define request language
        $request->setLocale($language);

        // define env locale
        putenv('LANGUAGE=' . $locale);
        putenv('LC_ALL=' . $locale);
        setlocale(LC_ALL, $locale);

        // define translator locale
        $this->translator->setLocale($language);
    }

    /**
     * Get preferred language from list of supported languages
     *
     * @param Request $request
     *
     * @return null|string
     */
    private function detectLanguage(Request $request)
    {
        // get from query parameter
        if (!empty($this->queryParameter)) {
            if ($request->query->has($this->queryParameter)) {
                $language = $request->query->get($this->queryParameter);
                if ($language && isset($this->supportedLocales[$language])) {
                    return $language;
                }
            }
        }

        // get from path
        if (true === $this->pathParameter) {
            $requestUri = $request->getRequestUri();
            $uriParts = explode('/', $requestUri);
            $language = !empty($uriParts[1]) ? $uriParts[1] : null;
            if ($language && isset($this->supportedLocales[$language])) {
                return $language;
            }
        }

        // get from cookie
        if (!empty($this->cookieParameter)) {
            if ($request->cookies->has($this->cookieParameter)) {
                $language = $request->cookies->get($this->cookieParameter);
                if ($language && isset($this->supportedLocales[$language])) {
                    return $language;
                }
            }
        }

        // get from header
        return $request->getPreferredLanguage(array_keys($this->supportedLocales));
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the Router to have access to the _locale
            KernelEvents::REQUEST => array(array('onKernelRequest', 16)),
        );
    }
}
