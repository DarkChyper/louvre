<?php

namespace Tests\AppBundle\Service;


use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\ZeroAmountException;
use AppBundle\Service\DateService;
use AppBundle\Service\MessagesFlashService;
use AppBundle\Service\OrderService;
use AppBundle\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class OrderServiceTest extends KernelTestCase
{
    private $dateService;
    private $entityManager;
    private $messageFlashService;
    private $sessionService;
    private $twig;
    private $mailer;

    public function setUp(){
        $this->dateService = new DateService();


        self::bootKernel();

        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->sessionService = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageFlashService = new MessagesFlashService(new Session(new MockArraySessionStorage()));
            /*$this->getMockBuilder(MessagesFlashService::class)
            ->disableOriginalConstructor()
            ->getMock();*/

        $this->twig = $this->getMockBuilder('Twig\Environment')
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->mailer = $this->getMockBuilder('Swift_Mailer')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    /**
     * @test
     * @dataProvider enoughTicketsProvider
     * @param $date
     * @param $nbrTicket
     * @param $expected
     */
    public function AreEnoughtTickets($date, $nbrTicket, $expected){

        $orderService = new OrderService($this->entityManager,$this->dateService,$this->messageFlashService,$this->sessionService,$this->twig, $this->mailer);

        $this->assertSame($expected, $orderService->areEnoughtTickets(\DateTime::createFromFormat('j-m-Y', $date),$nbrTicket));

    }

    public function enoughTicketsProvider(){
        return [
            ['04-03-2018',10,true],
            ['04-03-2019',1000,true],
            ['04-03-2020',1001,false],
        ];
    }

    /**
     * @test
     * @dataProvider categoryTicketPriceProvider
     * @param Order $orderExpected
     * @param Order $order
     */
    public function categoryTicketPrice( $expected, Order $order, $attribut){


        $orderService = new OrderService($this->entityManager,$this->dateService,$this->messageFlashService,$this->sessionService,$this->twig, $this->mailer);
        $orderService->calculateTotalPrice($order);
        $this->assertAttributeEquals($expected, $attribut, $order);
    }

    public function categoryTicketPriceProvider(){
        return [
            // CHILD TICKETS
            [8,
                $this->setOrderWithOneTicket("06-06-2020", "05-06-2016", false, "FULL"),
                "totalPrice"],
            [4,
                $this->setOrderWithOneTicket("06-06-2020", "05-06-2016", false, "HALF"),
                "totalPrice"],
            // STANDARD TICKETS
            [16,
                $this->setOrderWithOneTicket("06-06-2020", "04-06-2008", false, "FULL"),
                "totalPrice"],
            [8,
                $this->setOrderWithOneTicket("06-06-2020", "04-06-2008", false, "HALF"),
                "totalPrice"],
            [10,
                $this->setOrderWithOneTicket("06-06-2020", "04-06-2008", true, "FULL"),
                "totalPrice"],
            [5,
                $this->setOrderWithOneTicket("06-06-2020", "04-06-2008", true, "HALF"),
                "totalPrice"],
            // SENIOR TICKETS
            [12,
                $this->setOrderWithOneTicket("06-06-2020", "05-06-1959", false, "FULL"),
                "totalPrice"],
            [6,
                $this->setOrderWithOneTicket("06-06-2020", "05-06-1959", false, "HALF"),
                "totalPrice"],
            [10,
                $this->setOrderWithOneTicket("06-06-2020", "05-06-1959", true, "FULL"),
                "totalPrice"],
            [5,
                $this->setOrderWithOneTicket("06-06-2020", "05-06-1959", true, "HALF"),
                "totalPrice"],

        ];
    }

    /**
     * @test
     * @expectedException \AppBundle\Exception\ZeroAmountException
     * @dataProvider babyTicketPriceProvider
     */
    public function babyTicketPrice(Order $order){


        $orderService = new OrderService($this->entityManager,$this->dateService,$this->messageFlashService,$this->sessionService,$this->twig, $this->mailer);
        $orderService->calculateTotalPrice($order);
    }


    public function babyTicketPriceProvider(){
        return [
            // BABY TICKETS
            [$this->setOrderWithOneTicket("06-06-2020", "05-06-2018", false, "FULL")],
            [$this->setOrderWithOneTicket("06-06-2020", "05-06-2018", false, "HALF")],

        ];
    }

    /**
     * @param $visit String
     * @param $birth String
     * @param int true or false
     * @param $type String FULL or HALF
     * @param null $total int only for expected
     * @return Order
     */
    public function setOrderWithOneTicket($visit, $birth, $discount, $type, $total=null){
        $ticket = new Ticket();
        $ticket->setDiscount($discount);
        $ticket->setBirth(\DateTime::createFromFormat('j-m-Y', $birth));

        $order = new Order();
        $order->setTicketType($type);
        $order->setVisitDate(\DateTime::createFromFormat('j-m-Y', $visit));

        if($total !== null){
            $ticket->setPrice($total);
            $order->setTotalPrice($total);
        }
        $order->setTickets([$ticket]);
        return $order;
    }
}

