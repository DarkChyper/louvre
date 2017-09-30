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

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
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

    /**
     * @Route("/testing", name="testing")
     */
    public function testAction(SessionService $sessionService){
        $sessionService->deleteOrderInSession();
        $order = $sessionService->getOrCreateOrderSession();
        $order->setTicketNumber(1);
        $order->setMailContact("simon@lhoir.me");
        $order->setTicketType("FULL");
        $visitDate = new \DateTime("now");
        $visitDate->setDate(2017,11,30);
        $order->setVisitDate($visitDate);
        $order->setTotalPrice(16);

        $ticket = new Ticket();
        $ticket->setFname("Simon");
        $ticket->setName("Lhoir");
        $ticket->setCategory("std");
        $date = new \DateTime("now");
        $date->setDate(1986,03,04);
        $ticket->setBirth($date);
        $ticket->setDiscount(false);
        $ticket->setPrice(16);
        $ticket->setCountry("FR");

        $order->addTicket($ticket);

        $sessionService->saveOrderSession($order);

        return new RedirectResponse($this->generateUrl('payment'));



    }
}


