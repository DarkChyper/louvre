<?php

namespace AppBundle\Mailer;


use AppBundle\Entity\Order;
use Twig\Environment;

class LouvreMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * LouvreMailer constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param Order $order
     */
    public function sendTickets(Order $order){

        $message = (new \Swift_Message("Vos billets du Louvres."));

        $logo = $message->embed(\Swift_Image::fromPath('images/logo-louvre.jpg'));
        $body = $this->twig->render('mail\tickets.html.twig', array('order' => $order, 'logo' => $logo));

        $message->setBody($body,'text/html');

        $message->addTo($order->getMailContact());
        $message->addFrom("contact-louvre@simon-lhoir.fr");


        $this->mailer->send($message);
    }

}


