<?php

declare(strict_types=1);

namespace App\Validator\EntityExists;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EntityExistsValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! $constraint instanceof EntityExists) {
            return;
        }

        $repo = $this->em->getRepository($constraint->entity);
        $found = $repo->findOneBy([
            $constraint->field => $value,
        ]);

        if (! $found) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
