<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 16/09/2017
 * Time: 12:38
 */

namespace AppBundle\EventListener;

use AppBundle\Exception\OrderSessionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class OrderSessionExceptionListener
{
    private $router;
    
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent $event){
        // You get the exception object from the received event
        $exception = $event->getException();


        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof OrderSessionException) {
            $event->setResponse(new RedirectResponse($this->router->generate('homepage')));

        } else {
            return false;
        }

    }
}



