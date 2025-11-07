<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/login', methods: [Request::METHOD_POST])]
class Login extends AbstractController
{
    /**
     * Intercepted by LexikJWTAuthenticationBundle
     */
    public function __invoke(): void
    {
    }
}
