<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\Type\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $order = new Order();
        $orderForm = $this->createForm(OrderType::class, $order)->handleRequest($request);

        return $this->render('home/index.html.twig',array(
            'orderForm' => $orderForm->createView(),
        ));
    }
}


