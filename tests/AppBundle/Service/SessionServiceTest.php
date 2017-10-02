<?php

namespace Tests\AppBundle\Service;

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
    public function getOrCreateOrderSession(){
        $session = new Session(new MockArraySessionStorage());

        $sessionService = new SessionService($session);

        $sessionService->getOrderSession();
    }
}