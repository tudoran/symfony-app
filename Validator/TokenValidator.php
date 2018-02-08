<?php

namespace BackOfficeBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TokenValidator extends ConstraintValidator
{


    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return mixed
     */
    public function validate($value, Constraint $constraint = null)
    {
        exit(var_dump($value));
        if(is_null($value->has_clean_email)){

            $value->email = null;

            $this->context->buildViolation('Unable to generate token: token must accept a valid customer email address. None given')
                ->setParameter('%string%', $value->email)
                ->addViolation();

        }

    }


}