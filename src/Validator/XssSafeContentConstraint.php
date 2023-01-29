<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute]
class XssSafeContentConstraint extends Constraint
{
    public string $message = "This field contains a dangerous HTML content.";
}
