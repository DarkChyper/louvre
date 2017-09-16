<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 16/09/2017
 * Time: 15:45
 */

namespace AppBundle\Service;


use AppBundle\Entity\Order;
use Symfony\Component\Form\Form;

class OrderService
{
    protected $dateService;


    /**
     * OrderService constructor.
     */
    public function __construct(DateService $dateService)
    {
        $this->dateService = $dateService;
    }

    /**
     * @param Order $order
     * @param Form $orderForm
     * @return bool
     */
    public function checkOrderForm(Order $order, Form $orderForm){
        $retour = false;
        if($orderForm->isSubmitted() && $orderForm->isValid()){

            // check date
            // check ticket left
            if($this->dateService->isAvailableVisitDay($order->getVisitDate(), $order->getTicketType())
                && $this->areEnoughtTickets($order->getVisitDate(), $order->getTicketNumber())){
                // save order
                $retour = true;
            }
        }
        return $retour;
    }

    /**
     * Return true if $ticketNumber tickets still available
     * false otherwise
     *
     * @param \DateTime $visitDate
     * @param $ticketNumber
     * @return bool
     */
    private function areEnoughtTickets(\DateTime $visitDate, $ticketNumber){
        if($this->howManyTicketsLeft($visitDate) >= $ticketNumber){
            return true;
        }
        return false;
    }

    /**
     * Return the number of tickets left for the $visitDate
     *
     * @param \DateTime $visitDate
     * @return int
     */
    private function howManyTicketsLeft(\DateTime $visitDate){
        return 1000;
    }
}


