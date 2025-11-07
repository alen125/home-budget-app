<?php

declare(strict_types=1);

namespace App\Validator\EntityExists;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class EntityExists extends Constraint
{
    public function __construct(
        public string $entity,
        public string $field = 'id',
        public string $message = 'Entity not found.',
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }

    public function validatedBy(): string
    {
        return EntityExistsValidator::class;
    }
}
