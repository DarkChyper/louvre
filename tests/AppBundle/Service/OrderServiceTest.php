<?php

namespace Tests\AppBundle\Service;


use AppBundle\Entity\Order;
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
        $this->dateService = $this->getMockBuilder(DateService::class)
            ->disableOriginalConstructor()
            ->getMock();

        self::bootKernel();

        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->sessionService = $this->getMockBuilder(SessionService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageFlashService = $this->getMockBuilder(MessagesFlashService::class)
            ->disableOriginalConstructor()
            ->getMock();

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


}

