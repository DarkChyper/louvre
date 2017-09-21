<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 16/09/2017
 * Time: 15:45
 */

namespace AppBundle\Service;


use AppBundle\Entity\Order;
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
     * Return the number of tickets left for the $visitDate
     *
     * @param \DateTime $visitDate
     * @return int
     */
    private function howManyTicketsLeft(\DateTime $visitDate){
       return  $this->em->getRepository('AppBundle:Order')->countTicketsReserved($visitDate);

    }
}


