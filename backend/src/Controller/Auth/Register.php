<?php

namespace App\Controller\Auth;

use App\DTO\User\RegisterDTO;
use App\Service\User\RegistrationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/register', methods: [Request::METHOD_POST])]
class Register extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload]
        RegisterDTO $registerDTO,
        RegistrationHandler $handler,
    ): JsonResponse {
        return $this->json(
            data: $handler->handle($registerDTO),
            status: Response::HTTP_CREATED,
            context: [
                'groups' => ['user:single'],
            ],
        );
    }
}
