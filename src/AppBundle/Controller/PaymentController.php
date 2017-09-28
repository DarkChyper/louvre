<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Form\Type\OrderTicketsType;
use AppBundle\Form\Type\TicketType;
use AppBundle\Service\OrderService;
use AppBundle\Service\PaymentService;
use AppBundle\Service\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * @Route("/payment", name="payment")
     */
    public function paymentAction(Request $request, SessionService $sessionService, OrderService $orderService, PaymentService $paymentService)
    {

        $order = $sessionService->getOrderSession();

        $amount = $orderService->getTotalAmountToStrip();

        $publishableKey = $paymentService->getPublishableKey();


        return $this->render('payment/index.html.twig', array(
            'order' => $order,
            'amount' =>$amount,
            'publishableKey' => $publishableKey,
    ));
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkoutAction(Request $request, OrderService $orderService, PaymentService $paymentService){

        if($request->get("stripeToken")){

            $paymentService->proceedCheckout($request->get("stripeToken"));

            $orderService->Succeed();

            // show successfull page

        }
        return new Response("<html><body><h1>SUCCESS</h1></body></html>");

    }
}


