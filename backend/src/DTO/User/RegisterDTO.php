<?php

declare(strict_types=1);

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

readonly class RegisterDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        private ?string $email = null,
        #[Assert\NotBlank]
        #[Assert\Length(min: 6, max: 255)]
        private ?string $password = null,
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 255)]
        private ?string $name = null,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
