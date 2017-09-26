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

class PaymentController extends Controller
{
    /**
     * @Route("/payment", name="payment")
     */
    public function paymentAction(Request $request, SessionService $sessionService, OrderService $orderService)
    {

        $order = $sessionService->getOrderSession();

    dump($order);
        return $this->render('payment/index.html.twig', array(
            'order' => $order,
    ));
    }
}


