<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Form\Type\OrderTicketsType;
use AppBundle\Form\Type\TicketType;
use AppBundle\Service\OrderService;
use AppBundle\Service\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TicketsController extends Controller
{
    /**
     * @Route("/tickets", name="tickets")
     */
    public function ticketsAction(Request $request, SessionService $sessionService, OrderService $orderService)
    {

        $order = $orderService->setEmptyTickets($sessionService->getOrderSession());

        $ticketsForm = $this->createForm(OrderTicketsType::class, $order, array(
            'action' => $this->generateUrl("tickets"),
        ));

        $ticketsForm->handleRequest($request);

        if($ticketsForm->isSubmitted() && $ticketsForm->isValid()){

            $orderService->calculateTotalPrice($order);
            $sessionService->saveOrderSession($order);

            // got to validation
            return $this->forward('AppBundle:Payment:payment');
        }

        return $this->render('tickets/index.html.twig', array(
            "ticketsForm" => $ticketsForm->createView(),
        ));
    }
}


