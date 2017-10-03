<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function homepageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}

