<?php

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class NotPublicHollidayInFrance extends Constraint
{
    public $message = '{{ date }} est un jour férié en France. Il n\'est pas possible de réserver pour cette date.';
}


