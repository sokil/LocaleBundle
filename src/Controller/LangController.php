<?php

namespace Sokil\LocaleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class LangController extends Controller
{
    /**
     * @param Request $request
     * @param string $locale
     * @Route("/lang/set/{locale}", name="set_locale")
     * @Method({"GET"})
     * @return Response
     */
    public function setAction(Request $request, $locale)
    {
        // renew cookie
        $response = new Response();
        $response->headers->setCookie(new Cookie(
            'lang',
            $locale,
            time() + 30 * 24 * 60 * 60,
            '/'
        ));

        // back
        $backUrl = $request->get('back');
        if (!$backUrl || substr($backUrl, 0, 1) !== '/') {
            $backUrl = '/';
        }

        $response->headers->set('Location', $backUrl);

        return $response;
    }
}