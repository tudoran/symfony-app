<?php

namespace BackOfficeBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class TokenConstraint
 * @package BackOfficeBundle\Constraint
 */
class TokenConstraint extends Constraint
{

    public $message = 'Parameters passed in to token generator resulted in errors';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'TokenValidator';
    }


}