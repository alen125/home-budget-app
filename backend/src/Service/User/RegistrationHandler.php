<?php

declare(strict_types=1);

namespace App\Service\User;

use App\DTO\User\RegisterDTO;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class RegistrationHandler
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(RegisterDTO $registerDTO): User
    {
        $user = (new User())
            ->setEmail($registerDTO->getEmail())
            ->setName($registerDTO->getName())
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
        ;
        $user->setPassword($this->passwordHasher->hashPassword($user, $registerDTO->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
