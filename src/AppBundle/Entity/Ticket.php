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

/**
 * Ticket
 *
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
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
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     *
     */
    protected $order;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=30)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="fname", type="string", length=30)
     * @Assert\NotBlank()
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
     * @Assert\NotBlank()
     * @Assert\Date(message="La date doit être au format JJ/MM/YYYY")
     * @Assert\LessThan(value="tomorrow",message="La date de naissance ne peut être future.")
     */
    protected $birth;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     *
     */
    protected $price;

    /**
     * @var string
     * @ORM\Column(name="category", type="string", length=3)
     * @Assert\NotBlank();
     */
    protected $category;

    /**
    * @var int
    *
    * @ORM\Column(name="discount", type="boolean")
     *
    */
    protected $discount;


    /**
     * Ticket constructor.
     */
    public function __construct()
    {
        $this->price= 0;
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
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * @param string $fname
     */
    public function setFname($fname)
    {
        $this->fname = $fname;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return \DateTime
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * @param \DateTime $birth
     */
    public function setBirth($birth)
    {
        $this->birth = $birth;
    }

    /**
     * @return int
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param int $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }



}


