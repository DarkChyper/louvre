<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 16/09/2017
 * Time: 15:45
 */

namespace AppBundle\Service;


use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;

class OrderService
{
    protected $dateService;
    protected $em;


    /**
     * OrderService constructor.
     */
    public function __construct(EntityManager $entityManager,DateService $dateService)
    {
        $this->dateService = $dateService;
        $this->em = $entityManager;
    }

    /**
     * Return true if $ticketNumber tickets still available
     * false otherwise
     *
     * @param \DateTime $visitDate
     * @param $ticketNumber
     * @return bool
     */
    public function areEnoughtTickets(\DateTime $visitDate, $ticketNumber){
        if($this->howManyTicketsLeft($visitDate) >= $ticketNumber){
            return true;
        }
        return false;
    }

    /**
     * Set $order->getTicketNumber() empty ticket
     * @param Order $order
     */
    public function setEmptyTickets(Order $order){
        if($order->getTickets()->count() === 0){
            // new order = no tickets before
            return $this->initializeEmptyTickets($order);

        } elseif($order->getTickets()->count() !== $order->getTicketNumber()){
            // not new order and number of tickets changed => clean old tickets
            $order->setTickets(new ArrayCollection());
            return $this->initializeEmptyTickets($order);

        } else {
            // reload page ? change something in order
            // but not the number of tickets => nothing to do
            return $order;
        }
    }

    /**
     * @param $order
     */
    private function initializeEmptyTickets($order){

        for($i = 0; $i < $order->getTicketNumber(); $i++){

            $order->getTickets()->add(new Ticket());

        }
        return $order;
    }

    /**
     * Return the number of tickets left for the $visitDate
     *
     * @param \DateTime $visitDate
     * @return int
     */
    private function howManyTicketsLeft(\DateTime $visitDate){
       return (1000 - $this->em->getRepository('AppBundle:Order')->countTicketsReserved($visitDate));

    }


}


