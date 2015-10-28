<?php

namespace Wa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MdpValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        //die(dump(strlen($value)));
        if (strlen($value) < $constraint->min) {
            $this->context->buildViolation($constraint->message2)
                ->setParameter('{{ nb }}', $constraint->min)
                ->addViolation();
        }
        else{
            $passwords = ["admin", "admin75"];
            foreach($passwords as $pwd)
            {
                if (preg_match("#\b(".$pwd.")\b#ui", $value))
                {
                    $this->context->buildViolation($constraint->message)
                        ->addViolation();
                    return;
                }
            }
        }

    }
}