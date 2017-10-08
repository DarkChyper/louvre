<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Form\Type\OrderTicketsType;
use AppBundle\Form\Type\TicketType;
use AppBundle\Service\OrderService;
use AppBundle\Service\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TicketsController extends Controller
{
    /**
     * @Route("/tickets", name="tickets")
     * @Method({"GET", "POST"})
     */
    public function ticketsAction(Request $request, OrderService $orderService, SessionService $sessionService)
    {

        $order = $orderService->setEmptyTickets($sessionService->getOrderSession());

        $ticketsForm = $this->createForm(OrderTicketsType::class, $order, array(
            'action' => $this->generateUrl("tickets"),
        ));

        $ticketsForm->handleRequest($request);

        if($ticketsForm->isSubmitted() && $ticketsForm->isValid()){


            $orderService->calculateTotalPrice($order);
            return new RedirectResponse($this->generateUrl('payment'));
        }

        return $this->render('tickets/index.html.twig', array(
            "ticketsForm" => $ticketsForm->createView(),
            "order" => $order,
            "numberOfTickets" => $order->getTicketNumber(),
        ));
    }
}


