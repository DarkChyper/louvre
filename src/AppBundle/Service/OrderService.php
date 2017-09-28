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
use AppBundle\Exception\ZeroAmountException;
use AppBundle\Service\MessagesFlashService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;

/**
 * Class OrderService
 * @package AppBundle\Service
 */
class OrderService
{
    protected $dateService;
    protected $em;
    protected $mfs;
    protected $sessionsService;

    /* NUMBERS */

    CONST ZERO              = 0;
    CONST MIN_CHILD         = 4;
    CONST MAX_CHILD         = 12;
    CONST MIN_SENIOR        = 60;
    CONST CENT              = 100;
    CONST MAX_TICKETS       = 1000;

    /* DAY TYPE */
    const FULL              = "FULL";
    const HALF              = "HALF";

    /* TICKETS PRICE */
    CONST BABY_PRICE        = 0;
    CONST CHILD_PRICE       = 8;
    CONST STANDARD_PRICE    = 16;
    CONST SENIOR_PRICE      = 12;
    CONST DISCOUNT_PRICE    = 10;

    /* TICKET CATEGORY */
    CONST BABY              = "BBY";
    CONST CHILD             = "CHD";
    CONST STANDARD          = "STD";
    CONST SENIOR            = "SNR";

    /* QUOTIENT */
    CONST FULL_QUOTIENT     = 1.0;
    CONST HALF_QUOTIENT     = 0.5;

    /* MESSAGES */
    CONST MSG_NOT_ENOUGH_TICKET = "Il n'y a plus assez de place disponible pour votre réservation. Essayer de réduire le nombre de place ou de choisir une autre date.";



    /**
     * OrderService constructor.
     */
    public function __construct(EntityManagerInterface $entityManager,
                                DateService $dateService,
                                MessagesFlashService $messageFlashService,
                                SessionService $sessionService)
    {
        $this->dateService = $dateService;
        $this->em = $entityManager;
        $this->mfs = $messageFlashService;
        $this->sessionsService = $sessionService;
    }

    /**
     * Set $order->getTicketNumber() empty ticket
     * @param Order $order
     */
    public function setEmptyTickets(){

        $order = $this->sessionsService->getOrderSession();

        $order->setTotalPrice(self::ZERO);

        $ticketsSaved = $order->getTickets()->count();

        if($ticketsSaved === self::ZERO){
            // new order = no tickets before
            return $this->initializeEmptyTickets($order);

        }

        if($ticketsSaved < $order->getTicketNumber()){

            // add new tickets
            $toAdd = $order->getTicketNumber() - $ticketsSaved;
            dump($toAdd);
            for($i=0; $i < $toAdd; $i++){
                dump($i);
                $order->getTickets()->add(new Ticket());
            }
            return $order;

        } elseif($ticketsSaved > $order->getTicketNumber()){

            // delete any tickets
            $tabNewTickets = new ArrayCollection();
            $tickets = $order->getTickets();
            $toDel =  $order->getTicketNumber();
            for($i = self::ZERO; $i < $toDel; $i++){
                $tabNewTickets->add($tickets[$i]);
            }

            $order->setTickets($tabNewTickets);

            return $order;

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
    public function calculateTotalPrice(){
        $order = $this->sessionsService->getOrderSession();
        $ticketsArray = $order->getTickets();
        foreach($ticketsArray as $ticket){

            $this->calculateTicketPrice($order, $ticket,$ticket->getDiscount());

        }
        if($order->getTotalPrice() === 0){
            throw new ZeroAmountException("Il n'est pas possible de commander uniquement des billets pour enfant en bas âge.");
        }
        $this->sessionsService->saveOrderSession($order);
    }


    /**
     * @param $order Order
     * @param $age integer
     * @param $discount boolean
     * @return int
     */
    private function calculateTicketPrice(Order $order,Ticket $ticket, $discount){

        $age = $this->dateService->calculateAge($ticket->getBirth(),$order->getVisitDate());
        $price = self::BABY_PRICE;

        switch(true){

            case ($age >= self::MIN_CHILD && $age <= self::MAX_CHILD):
                $price =  self::CHILD_PRICE * $this->getQuotient($order->getTicketType());
                $ticket->setDiscount(false);// child cannot have a discount ticket
                $ticket->setCategory(self::CHILD);
                break;

            case (($age > self::MAX_CHILD && $age < self::MIN_SENIOR) && !$discount):
                $price = self::STANDARD_PRICE * $this->getQuotient($order->getTicketType());
                $ticket->setCategory(self::STANDARD);
                break;

            case (($age > self::MAX_CHILD  && $age < self::MIN_SENIOR) && $discount):
                $price = self::DISCOUNT_PRICE * $this->getQuotient($order->getTicketType());
                $ticket->setCategory(self::STANDARD);
                break;

            case ($age >= self::MIN_SENIOR && !$discount):
                $price = self::SENIOR_PRICE * $this->getQuotient($order->getTicketType());
                $ticket->setCategory(self::SENIOR);
                break;

            case ($age >= self::MIN_SENIOR && $discount):
                $price = self::DISCOUNT_PRICE * $this->getQuotient($order->getTicketType());
                $ticket->setCategory(self::SENIOR);
                break;

            default:
                $price = self::BABY_PRICE * $this->getQuotient($order->getTicketType());
                $ticket->setCategory(self::BABY);
                $ticket->setDiscount(false);// baby cannot have a discount ticket
                break;
        }

        $ticket->setPrice((int) $price);
        $order->setTotalPrice($order->getTotalPrice() + (int) $price);
    }

    /**
     * @param $string
     * @return float|int
     */
    private function getQuotient($string){
        if(self::FULL === $string){
            return self::FULL_QUOTIENT;
        } else {
            return self::HALF_QUOTIENT;
        }
    }

    /**
     * @param $order
     */
    private function initializeEmptyTickets(Order $order){
        $limit =  $order->getTicketNumber();
        for($i = self::ZERO; $i < $limit ; $i++){

            $order->getTickets()->add(new Ticket());

        }
        return $order;
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
        $this->mfs->messageError(self::MSG_NOT_ENOUGH_TICKET);
        return false;
    }

    /**
     * Return the number of tickets left for the $visitDate
     *
     * @param \DateTime $visitDate
     * @return int
     */
    private function howManyTicketsLeft(\DateTime $visitDate){
       return (self::MAX_TICKETS - $this->em->getRepository('AppBundle:Order')->countTicketsReserved($visitDate));

    }

    /**
     * @return string
     */
    public function getContactMail(){
        return $this->sessionsService->getOrderSession()->getMailContact();
    }

    /**
     * Stripe need a int including cents
     *
     * @return int
     */
    public function getTotalAmountToStrip(){

        return self::CENT * ($this->sessionsService->getOrderSession()->getTotalPrice());
    }

    public function succeed(){

        // set purchaseDate

        // set resevation code

        // register on database
        $this->saveInDataBase();

        // send a mail with tickets
        $this->sendTickets();

        $mail = $this->sessionsService->getOrderSession()->getMailContact();

        // clean session
        $this->sessionsService->deleteOrderInSession();

        $this->mfs->messageSuccess("Tansaction validée");
        $this->mfs->messageSuccess("Les tickets ont été envoyés à l'adresse '".$mail."'' .");
    }

    private function saveInDataBase(){

    }

    private function sendTickets(){

    }



}


