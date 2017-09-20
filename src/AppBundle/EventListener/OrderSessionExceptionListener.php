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
        $message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof OrderSessionException) {
            $response = new RedirectResponse($this->router->generate('homepage'));
        } elseif($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}



