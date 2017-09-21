<?php

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AvailableVisitDay extends Constraint
{
    public $message = "Il n'est pas possible de réserver des billets les mardis et dimanches.";
}


