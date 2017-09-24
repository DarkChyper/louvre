<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 24/09/2017
 * Time: 17:58
 */

namespace AppBundle\Validator\Constraints;


use AppBundle\Entity\Order;
use AppBundle\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EnoughTicketsValidator extends ConstraintValidator
{

    /**
    * @var EntityManagerInterface
    */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($order, Constraint $constraint)
    {
        $ticketsReserved = $this->em->getRepository('AppBundle:Order')->countTicketsReserved($order->getVisitDate());

        if((1000 - $ticketsReserved) < $order->getTicketNumber()){

            $this->context->buildViolation($constraint->message)
                ->addViolation();

        }
    }

}


