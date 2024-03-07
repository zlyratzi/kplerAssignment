<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandler
{
    public static function handle(\Exception $e, int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], $e->getCode() ?: $statusCode);
    }
}
