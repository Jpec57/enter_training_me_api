<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TimeUnderTensionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\TimeUnderTension */

        if (null === $value || '' === $value) {
            return;
        }

        $valuesArr = explode("/", $value);
        if (count($valuesArr) != 4) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
        foreach ($valuesArr as $val) {
            $parsedVal = intval($val);
            if ($val < 0 || $val > 60) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            }
        }
    }
}
