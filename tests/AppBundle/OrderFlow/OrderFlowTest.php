<?php

namespace Tests\AppBundle\OrderFlow;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderFlowTest extends WebTestCase
{
    /**
     * @test
     */
    public function testorderFlow()
    {
        $client = static::createClient();

        // Go to homepage
        $crawler = $client->request('GET', '/');

        // get Form
        $buttonCrawler = $crawler->selectButton('order[suivant]');
        $form = $buttonCrawler->form(array(
            'order[visitDate]' => "03/10/2019",
            'order[mailContact][first]' => "simon@lhoir.me",
            'order[mailContact][second]' => "simon@lhoir.me"
        ));
        $form['order[ticketType]']->select("HALF");

        // submit form
        $crawler = $client->submit($form);

        // follow redirect to tickets page
        $crawler = $client->followRedirect();

        // get form
        $buttonCrawler = $crawler->selectButton('order_tickets[suivant]');

        $formTickets = $buttonCrawler->form(array(
            'order_tickets[tickets][0][name]' => "Lhoir",
            'order_tickets[tickets][0][fname]' => "Simon",
            'order_tickets[tickets][0][birth]' => "04/03/1986"
        ));

        $formTickets['order_tickets[tickets][0][discount]']->tick();

        $crawler = $client->submit($formTickets);

        // follow redirect to payment page
        $crawler = $client->followRedirect();


        $this->assertSame(1, $crawler->filter('html:contains("Simon Lhoir")')->count(),"Problème sur prenom et nom.");
        $this->assertSame(1, $crawler->filter('html:contains("04/03/1986")')->count(), "Problème sur la date de naissance");
        $this->assertSame(1, $crawler->filter('h4:contains("03/10/2019")')->count(), "Problème sur la date de visite");
        $this->assertSame(1, $crawler->filter('html:contains("5€")')->count(), "Problème sur le prix du billet");
        $this->assertSame(1, $crawler->filter('span:contains("5€")')->count(), "Problème sur le prix total");
        $this->assertSame(1, $crawler->filter('html:contains("demi-journée")')->count(), "Problème le type de billet");


    }


}