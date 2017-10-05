<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Form\Type\OrderType;
use AppBundle\Service\OrderService;
use AppBundle\Service\SessionService;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, SessionService $sessionService)
    {
        $order = $sessionService->getOrCreateOrderSession();
        $orderForm = $this->createForm(OrderType::class, $order)->handleRequest($request);

        if($orderForm->isSubmitted() && $orderForm->isValid()){

            // save in session
            $sessionService->saveOrderSession($order);

            // go to ticket view
            return new RedirectResponse($this->generateUrl('tickets'));

        }

        return $this->render('home/index.html.twig',array(
            'orderForm' => $orderForm->createView(),
        ));
    }

}


