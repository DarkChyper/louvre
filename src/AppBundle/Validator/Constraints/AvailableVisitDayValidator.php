<?php

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AvailableVisitDayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $date = date_format($value, 'N');
        // Tuesday or Sunday
        if($date === "2"
            || $date === "7" ){

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}


