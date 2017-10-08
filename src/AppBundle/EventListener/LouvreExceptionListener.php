<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 16/09/2017
 * Time: 12:38
 */

namespace AppBundle\EventListener;

use AppBundle\Exception\CheckoutException;
use AppBundle\Exception\NotFilledTicketsException;
use AppBundle\Exception\OrderSessionException;
use AppBundle\Exception\ZeroAmountException;
use AppBundle\Service\MessagesFlashService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class LouvreExceptionListener
{
    private $router;
    private $mfs;

    public function __construct(RouterInterface $router,MessagesFlashService $messagesFlashService)
    {
        $this->router = $router;
        $this->mfs = $messagesFlashService;
    }

    public function onKernelException(GetResponseForExceptionEvent $event){
        // You get the exception object from the received event
        $exception = $event->getException();

        if ($exception instanceof OrderSessionException) {
            $event->setResponse($this->catchException('homepage'));
        } elseif($exception instanceof ZeroAmountException){
            $event->setResponse($this->catchException('tickets',$exception->getMessage()));
        } elseif($exception instanceof CheckoutException) {
            $event->setResponse($this->catchException('payment',$exception->getMessage()));

        }elseif($exception instanceof NotFilledTicketsException) {
            $event->setResponse($this->catchException('tickets',$exception->getMessage()));

        }else {
            return false;
        }

    }

    /**
     * @param $route
     * @param null $message
     * @return RedirectResponse
     */
    private function catchException($route, $message=null){
        if($message !== null){
            $this->mfs->messageError($message);
        }
        return new RedirectResponse($this->router->generate($route));
    }
}



