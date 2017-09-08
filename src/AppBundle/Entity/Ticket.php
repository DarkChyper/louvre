<?php
/**
 * Entity Ticket
 * User: DarkChyper
 * Date: 08/09/2017
 * Time: 13:12
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class Ticket
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    protected $order;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=4)
     * @Assert/NotBlank()
     * HALF OR FULL
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=30)
     * @Assert/NotBlank()
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="fname", type="string", length=30)
     * @Assert/NotBlank()
     */
    protected $fname;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=2)
     * @Assert\Country()
     */
    protected $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth", type="date", nullable=false)
     * @Assert\Date(message="La date doit être au format JJ/MM/YYYY")
     */
    protected $birth;

    /**
    * @var int
    *
    * @ORM\Column(name="discount", type="boolean")
     *
    */
    protected $discount;
}


