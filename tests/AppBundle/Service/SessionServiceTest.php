<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Service\SessionService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionServiceTest extends TestCase
{

    /**
     * @test
     * @expectedException \AppBundle\Exception\OrderSessionException
     */
    public function getOrderSession(){
        $session = new Session(new MockArraySessionStorage());

        $sessionService = new SessionService($session);

        $sessionService->getOrderSession();
    }

    /**
     * @test
     */
    public function getOrCreateOrderSession(){
        $session = new Session(new MockArraySessionStorage());

        $sessionService = new SessionService($session);

        $order = new Order();

        $this->assertEquals($order, $sessionService->getOrCreateOrderSession());
    }

}

