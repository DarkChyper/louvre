<?php
/**
 * Order Entity
 * User: DarkChyper
 * Date: 08/09/2017
 * Time: 13:12
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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



}

