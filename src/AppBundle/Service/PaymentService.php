<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 28/09/2017
 * Time: 13:12
 */

namespace AppBundle\Service;

/**
 * Class PaymentService
 * @package AppBundle\Service
 */
class PaymentService
{
    const SECRET_KEY = "sk_test_6kRr310K4GcJYPB3bN9qAI9P";
    const PUBLISHABLE_KEY = "pk_test_vvB081qdlP8M3HdlNvAx4Kmq";

    /**
     * PaymentService constructor.
     */
    public function __construct()
    {
        \Stripe\Stripe::$apiKey = self::SECRET_KEY;
    }

    /**
     * @return string publishable key
     */
    public function getPublishableKey(){
        return self::PUBLISHABLE_KEY;
    }

}