<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 03/10/2017
 * Time: 19:35
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function paymentPageRedirectionIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/payment');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function checkoutPageRedirectionIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/checkout');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }
}

