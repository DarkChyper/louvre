<?php

namespace Tests\AppBundle\Service;


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

        $this->sessionService = new SessionService(new Session(new MockArraySessionStorage()));

        $this->messageFlashService = new MessagesFlashService(new Session(new MockArraySessionStorage()));

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

        $this->assertSame($expected, $orderService->areEnoughtTickets($date,$nbrTicket));

    }

    public function enoughTicketsProvider(){
        return [
            [\DateTime::createFromFormat('j-m-Y H:i:s', '04-03-2018 00:00:00'),10,true],
            [\DateTime::createFromFormat('j-m-Y H:i:s', '04-03-2019 00:00:00'),1000,true],
            [\DateTime::createFromFormat('j-m-Y H:i:s', '04-03-2020 00:00:00'),1001,false],
        ];
    }


}