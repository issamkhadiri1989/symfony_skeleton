<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordConstraintValidator extends ConstraintValidator
{
    /**
     * A valid password must contain at least 1 upper, 1 lower, 1 digit and at least of 8 chars.
     */
    private const PATTERN = '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/';

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof PasswordConstraint) {
            throw new UnexpectedTypeException($constraint, PasswordConstraint::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        $isPasswordMatched = \preg_match(self::PATTERN, $value);
        if (0 === $isPasswordMatched) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
