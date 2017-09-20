<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Service\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TicketsController extends Controller
{
    /**
     * @Route("/tickets", name="tickets")
     */
    public function ticketsAction(Request $request, SessionService $sessionService)
    {

        return $this->render('tickets/index.html.twig');
    }
}


