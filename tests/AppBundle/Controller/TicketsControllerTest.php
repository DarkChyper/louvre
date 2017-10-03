<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 03/10/2017
 * Time: 19:35
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketsControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function ticketsPageRedirectionIsUp()
    {
        $client = static::createClient();
        $client->request('GET', 'tickets');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }
}