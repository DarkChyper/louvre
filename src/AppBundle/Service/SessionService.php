<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 16/09/2017
 * Time: 12:05
 */

namespace AppBundle\Service;


use AppBundle\Entity\Order;
use AppBundle\Exception\OrderSessionException;
use SensioLabs\Security\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService
{
    protected $session;

    /**
     * SessionService constructor.
     * @param $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     *  get or create Order in session
     * @return Order
     */
    public function getOrCreateOrderSession(){
        if(! $this->session->has("order")){
            $this->setOrderSession(new Order());
        }
        return $this->getOrderSession();
    }

    /**
     *  Get Order in session or throw new OrderSessionException
     * @return Order
     */
    public function getOrderSession(){
        if(! $this->session->has("order")){
            throw new OrderSessionException("No order in session");
        }
        return $this->session->get("order");
    }

    /**
     * Set Order in session
     * @param Order $order
     */
    public function setOrderSession(Order $order){
        $this->session->set("order", $order);
    }

}



