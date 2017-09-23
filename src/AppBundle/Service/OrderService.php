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
use AppBundle\Service\MessagesFlashService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;

class OrderService
{
    protected $dateService;
    protected $em;
    protected $mfs;


    /**
     * OrderService constructor.
     */
    public function __construct(EntityManagerInterface $entityManager,DateService $dateService,MessagesFlashService $messageFlashService)
    {
        $this->dateService = $dateService;
        $this->em = $entityManager;
        $this->mfs = $messageFlashService;
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
        $this->mfs->messageError("Il n'y a plus assez de place disponible pour votre réservation. Essayer de réduire le nombre de place ou de choisir une autre date.");
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
     * Calculate total price depending of tickets
     *
     * @param Order $order
     */
    public function calculateTotalPrice(Order $order){
        $total = 0;
        $ticketsArray = $order->getTickets();
        foreach($ticketsArray as $ticket){

            $total += $this->calculateTicketPrice($this->dateService->calculateAge($ticket->getBirth()),$ticket->getDiscount());

        }

        $order->setTotalPrice($total);
    }


    /**
     * @param $age integer
     * @param $discount boolean
     * @return int
     */
    private function calculateTicketPrice($age, $discount){
        $price = 0;

        switch(true){
            case ($age >= 4 && $age <= 12):
                $price = 8;
                break;
            case ($age > 12 && $age < 60):
                $price = 16;
                break;
            case ($age >= 60):
                $price = 12;
                break;
            default:
                $price = 0;
                break;
        }

        if($discount && $price > 10 ){
            $price = 10;
        }

        return $price;
    }

    /**
     * @param $order
     */
    private function initializeEmptyTickets(Order $order){
        $limit =  $order->getTicketNumber();
        for($i = 0; $i < $limit ; $i++){

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


