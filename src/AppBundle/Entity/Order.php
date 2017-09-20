<?php
/**
 * Order Entity
 * User: DarkChyper
 * Date: 08/09/2017
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as LouvreAssert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * Order
 *
 * @ORM\Table(name="order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Order
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visit_date", type="date", nullable=false)
     * @Assert\Date(message="La date doit être au format JJ/MM/YYYY")
     * @Assert\GreaterThan(value="yesterday",message="Il n'est pas possible d'acheter un billet pour une date passée.")
     * @LouvreAssert\AvailableVisitDay
     * @LouvreAssert\NotPublicHollidayInFrance
     */
    protected $visitDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchase_date", type="date", nullable=false)
     * @Assert\Date(message="La date doit être au format JJ/MM/YYYY")
     */
    protected $purchaseDate;

    /**
     * @var string
     *
     * @ORM\Column(name="mail_contact", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Email
     */
    protected $mailContact;

    /**
     * @var int
     * @ORM\Column(name="ticket_number", type="integer")
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(20)
     */
    protected $ticketNumber;

    /**
     * @var string
     * @ORM\Column(name="ticket_type", type="string", length=4)
     * @Assert\NotBlank()
     *
     */
    protected $ticketType;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="order")
     */
    protected $tickets;

    /**
     * @var int
     * @ORM\Column(name="total_price", type="integer", nullable=false)
     * @Assert\GreaterThanOrEqual(0)
     */
    protected $totalPrice;

    /**
     * @var string
     * @ORM\Column(name="booking_code", type="string", length=50)
     */
    protected $bookingCode;

    /**
     * @var int
     * @ORM\Column(name="used", type="integer")
     *
     */
    protected $used;


    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->used = 0; // tickets are not used
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * @param \DateTime $visitDate
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;
    }

    /**
     * @return \DateTime
     */
    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    /**
     * @param \DateTime $purchaseDate
     */
    public function setPurchaseDate($purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;
    }

    /**
     * @return string
     */
    public function getMailContact()
    {
        return $this->mailContact;
    }

    /**
     * @param string $mailContact
     */
    public function setMailContact($mailContact)
    {
        $this->mailContact = $mailContact;
    }

    /**
     * @return int
     */
    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    /**
     * @param int $ticketNumber
     */
    public function setTicketNumber($ticketNumber)
    {
        $this->ticketNumber = $ticketNumber;
    }

    /**
     * @return string
     */
    public function getTicketType()
    {
        return $this->ticketType;
    }

    /**
     * @param string $ticketType
     */
    public function setTicketType($ticketType)
    {
        $this->ticketType = $ticketType;
    }

    /**
     * @return mixed
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param mixed $tickets
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;
    }

    /**
     * @return int
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param int $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return string
     */
    public function getBookingCode()
    {
        return $this->bookingCode;
    }

    /**
     * @param string $bookingCode
     */
    public function setBookingCode($bookingCode)
    {
        $this->bookingCode = $bookingCode;
    }

    /**
     * @return int
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * @param int $used
     */
    public function setUsed($used)
    {
        $this->used = $used;
    }

    /**
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Order
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        $today = new \DateTime("now");
        $time = $today->format('H');
        $today->setTime(0, 0, 0);

        if($this->getVisitDate() == $today && $time >=14 && $this->getTicketType() == 'FULL'){
            $context->buildViolation('Il n\'est pas possible de réserver un billet pour la journée complète après 14h00.')
                ->addViolation();
        }
    }
}

