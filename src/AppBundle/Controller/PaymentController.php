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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * @Route("/payment", name="payment")
     * @Method({"GET"})
     */
    public function paymentAction( SessionService $sessionService, OrderService $orderService, PaymentService $paymentService)
    {

        $order = $sessionService->getOrderSession();

        $orderService->checkOrder($order);

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
     * @Method({"GET", "POST"})
     */
    public function checkoutAction(Request $request, OrderService $orderService, PaymentService $paymentService, SessionService $sessionService){

        $order = $sessionService->getOrderSession(); // protect from direct access here

        $paymentService->proceedCheckout($request->get("stripeToken"));

        $orderService->Succeed($order);

        return new RedirectResponse($this->generateUrl('homepage'));
    }

}


