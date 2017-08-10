<?php

namespace AppBundle\Constraint;
use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class AvoidStress extends Constraint
{
    public $message = 'Unable to share the resource %string% because there was an entry in the last 10 seconds, please calm down and come in some times';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'avoid_stress_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}