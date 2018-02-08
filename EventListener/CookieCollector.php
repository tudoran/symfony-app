<?php

namespace BackOfficeBundle\EventListener;

use BackOfficeBundle\CookieCollectorInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class CookieCollector
 * @package BackOfficeBundle\EventListener
 */
class CookieCollector implements CookieCollectorInterface
{

    /**
     * Clean up request cookies content required for toast module notifications
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event){

        $request = $event->getController();

        try {

            if(is_array($request))
                return;

            $request[0]->request->cookies->remove(self::TOAST_DISPLAY_KEY);
            $request[0]->request->cookies->remove(self::TOAST_DISPLAY_MESSAGE);

        }catch(\Exception $e){
            // TODO log
        }


    }

    /**
     * Clean up response cookies content required for toast module notifications
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {

        $response = $event->getResponse();

        $cookies = $response->headers->getCookies();

        if(count($cookies)) {

            foreach ($cookies as $cookie) {

                if (self::TOAST_KEY === $cookie->getName()) {
                    $response->headers->setCookie(new Cookie(self::TOAST_DISPLAY_KEY, 1));
                    $response->headers->setCookie(new Cookie(self::TOAST_DISPLAY_MESSAGE, $cookie->getValue()));
                }
            }

        } else {

            $response->headers->setCookie(new Cookie(self::TOAST_DISPLAY_KEY, 0));
            $response->headers->setCookie(new Cookie(self::TOAST_DISPLAY_KEY, 0));
        }
    }
}