<?php

namespace App\Helpers;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ExceptionResponseHelper
{
    private static function throwClientError(string $message, int $statusCode): JsonResponse
    {
        throw new HttpResponseException(response([
            "error" => $message
        ], $statusCode));
    }

    public static function throwInvariantError(string $message): JsonResponse
    {
        return self::throwClientError($message, 400);
    }

    public static function throwAuthenticationError(string $message): JsonResponse
    {
        return self::throwClientError($message, 401);
    }

    public static function throwForbiddenError(string $message): JsonResponse
    {
        return self::throwClientError($message, 403);
    }

    public static function throwNotFoundError(string $message): JsonResponse
    {
        return self::throwClientError($message, 404);
    }
}
