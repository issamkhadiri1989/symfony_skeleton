<?php

namespace App\Validator;

use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class XssSafeContentConstraintValidator extends ConstraintValidator
{
    public function __construct(private readonly HtmlSanitizerInterface $sanitizer)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof XssSafeContentConstraint) {
            throw new UnexpectedTypeException($constraint, XssSafeContentConstraint::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        $sanitizedContent = $this->sanitizer->sanitize($value);
        if ($sanitizedContent !== $value) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
