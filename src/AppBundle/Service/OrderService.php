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
use AppBundle\Mailer\LouvreMailer;
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
    /**
     * @var DateService
     */
    protected $dateService;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var \AppBundle\Service\MessagesFlashService
     */
    protected $mfs;

    /**
     * @var SessionService
     */
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
    const TANSACTION_VALIDEE    = "Tansaction validée";
    const TICKETS_SENT_TO       = "Les tickets ont été envoyés à l'adresse '";
    const BOOKING_CODE_MSG      = "La référence de votre achat est : '";
    const NOT_ALPHA_NUM_REGEX = "[^a-zA-Z0-9\ ]";
    const EMPTY_STRING = "";


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
            for($i=self::ZERO; $i < $toAdd; $i++){
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

    /**
     * Checkout Succeed so generate and save tickets and mail
     */
    public function succeed(){

        $this->setPurchaseDate();

        $this->setBookingCode();

        $this->saveInDataBase();

        $this->sendTickets();

        $mail = $this->sessionsService->getOrderSession()->getMailContact();
        $bookingCode = $this->sessionsService->getOrderSession()->getBookingCode();

        // clean session
        $this->sessionsService->deleteOrderInSession();

        $this->mfs->messageSuccess(self::TANSACTION_VALIDEE);
        $this->mfs->messageSuccess(self::TICKETS_SENT_TO .$mail."' .");
        $this->mfs->messageSuccess(self::BOOKING_CODE_MSG .$bookingCode."' .");
    }

    /**
     * Set the purchase date (now) in the order in session
     */
    private function setPurchaseDate(){
        $order = $this->sessionsService->getOrderSession();
        $order->setPurchaseDate(new \DateTime('now'));
        $this->sessionsService->saveOrderSession($order);
    }

    /**
     * generate a booking code like YYYYMMDDHHMMSSXX########
     * and save in order session
     */
    private function setBookingCode(){
        $order = $this->sessionsService->getOrderSession();
        $string = preg_replace(self::NOT_ALPHA_NUM_REGEX, self::EMPTY_STRING, $order->getMailContact());
        $bookingCode = $order->getPurchaseDate()->format("YmdHMS")
            . substr($string, 0, 1)
            . substr($string, strlen($string)-1, 1)
            . $order->getTicketNumber()
            . ($order->getTotalPrice() * 100);
        $order->setBookingCode($bookingCode);
        $this->sessionsService->saveOrderSession($order);
    }

    /**
     * save the order in session into the database
     */
    private function saveInDataBase(){

        $this->em->persist($this->sessionsService->getOrderSession());
        $this->em->flush();
    }

    /**
     * send tickets by mail
     */
    private function sendTickets(){
    }



}


