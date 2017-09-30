<?php

namespace AppBundle\Validator\Constraints;


use AppBundle\Service\DateService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotPublicHollidayInFranceValidator extends ConstraintValidator
{
    protected $dateService;

    /**
     * NotPublicHollidayInFranceValidator constructor.
     * @param $dateService
     */
    public function __construct(DateService $dateService)
    {
        $this->dateService = $dateService;
    }


    public function validate($value, Constraint $constraint)
    {
        if($this->dateService->isPublicHolidayInFrance($value)){
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{ date }}",$value->format("d/m/Y"))
                ->addViolation();
        }
    }
}


