<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class JsonRequestListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (str_starts_with($request->getPathInfo(), '/api')) {
            $request->headers->set('Accept', 'application/json');
        }
    }
}
