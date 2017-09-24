<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 24/09/2017
 * Time: 17:58
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * @Annotation
 */
class EnoughTickets extends Constraint
{
    public $message = "Il n'y a plus assez de place disponible pour votre réservation. Essayer de réduire le nombre de place ou de choisir une autre date.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}


