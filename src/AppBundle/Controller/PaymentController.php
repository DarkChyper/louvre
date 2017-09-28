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

            $token = $request->get("stripeToken");

            $customer = \Stripe\Customer::create(array(
                'email' => $orderService->getContactMail(),
                'source'  => $token
            ));

            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $orderService->getTotalAmountToStrip(),
                'currency' => 'eur'
            ));

            // if ok
            // register on database
            // send a mail with tickets
            // clean session
            // show successfull page

        }


    }
}


