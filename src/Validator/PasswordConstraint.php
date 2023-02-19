<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute]
class PasswordConstraint extends Constraint
{
    public string $message = 'The password must contain at least 1 uppercase and 1 lowercase';
}
