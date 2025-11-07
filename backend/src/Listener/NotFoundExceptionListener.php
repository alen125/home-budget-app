<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (! $exception instanceof NotFoundHttpException) {
            return;
        }
        $request = $event->getRequest();

        if ($request->attributes->get('_route_params') === []) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'error' => 'Entity not found!',
        ], Response::HTTP_NOT_FOUND));
    }
}
