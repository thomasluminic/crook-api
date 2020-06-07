<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $message = sprintf(
        'Votre ressource %s n\'a pas pu être récupéré',
        $exception->getMessage()
        );

        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getCode();
        } else {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        $response = new JsonResponse($message, $code);

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
