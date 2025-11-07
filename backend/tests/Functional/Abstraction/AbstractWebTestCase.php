<?php

declare(strict_types=1);

namespace App\Tests\Functional\Abstraction;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    protected function getOneEntityBy(string $entityClass, array $criteria): object
    {
        $container = $this->client->getContainer();
        $em = $container->get(EntityManagerInterface::class);

        return $em->getRepository($entityClass)->findOneBy($criteria);
    }

    protected function makeRequest(string $method, string $uri, int $expectedStatus, array $payload = []): array
    {
        $this->authenticateClient('test@example.com');

        $this->client->request(
            method: $method,
            uri: $uri,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload),
        );

        $this->assertResponseStatusCodeSame($expectedStatus);

        return json_decode($this->client->getResponse()->getContent(), true);
    }

    protected function authenticateClient(string $userEmail): void
    {
        $container = $this->client->getContainer();
        $em = $container->get(EntityManagerInterface::class);

        $user = $em->getRepository(User::class)->findOneBy(['email' => $userEmail]);
        if (!$user instanceof User) {
            throw new RuntimeException("User '$userEmail' not found in DB.");
        }

        $jwtManager = $container->get(JWTTokenManagerInterface::class);
        $token = $jwtManager->create($user);

        $this->client->setServerParameter('HTTP_Authorization', 'Bearer ' . $token);
    }
}