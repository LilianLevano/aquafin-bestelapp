<?php

namespace App\Http\Controllers;

use ReflectionClass;
use Closure;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

/**
 * Base controller for the application.
 * Provides shared functionality to other controllers.
 */
abstract class Controller
{
    /**
     * Handles a controller action, standardizing structure and error handling for both web redirects and API JSON responses.
     *
     * Example usage:
     *   $this->handleWithCases($request, fn ($request) => ..., [200 => [...], 422 => [...], 500 => [...]], JsonResponse::class);
     *
     * @template T of JsonResponse | RedirectResponse
     * @param Request $request
     * @param Closure(Request): (mixed) $methodToTry Main logic that may throw, result will be returned unless an exception is thrown
     * @param array{
     *   200: array{message: string, route?: string},
     *   422: array{message: string, route?: string, data?: mixed},
     *   500: array{message: string, route?: string, data?: mixed}
     * } $caseData
     * @param class-string<T> $responseType Either RedirectResponse::class or JsonResponse::class (default is Redirect)
     * @param bool $debug Attach debug info to output
     * @return T
     */
    public function handleWithCases(
        Request $request,
        Closure $methodToTry,
        array $caseData,
        string $responseType = RedirectResponse::class,
        bool $debug = false
    ) {
        try {
            $data = $methodToTry($request);
            return $this->handleSuccessResponse(
                $caseData['200']['message'],
                $caseData['200']['route'] ?? url()->current(),
                $data ?? null,
                $responseType
            );
        } catch (ValidationException $exception) {
            return $this->handleErrorResponse(
                $caseData['422']['message'],
                $caseData['422']['route'] ?? url()->current(),
                $exception->errors(),
                $caseData['422']['data'] ?? null,
                $responseType,
                $debug
            );
        } catch (QueryException $exception) {
            $errorType = (new ReflectionClass($exception))->getShortName();
            $errorCode = $exception->getCode();
            $shortMessage = 'A database error occurred';

            // Map common SQLSTATE codes to descriptions
            $knownErrors = [
                // See https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html
                '23000' => 'Integrity constraint violation',
                '23505' => 'Unique constraint violation',
                '42000' => 'Syntax error or access rule violation',
                'HY000' => 'General database error'
            ];
            $shortMessage = $knownErrors[$errorCode] ?? $shortMessage;
            $displayMessage = "Database error ($errorCode): $shortMessage.";

            return $this->handleErrorResponse(
                $caseData['500']['message'],
                $caseData['500']['route'] ?? url()->current(),
                [$errorType => [$displayMessage]],
                $caseData['500']['data'] ?? null,
                $responseType,
                $debug
            );
        } catch (Throwable $exception) {
            return $this->handleErrorResponse(
                $caseData['500']['message'],
                $caseData['500']['route'] ?? url()->current(),
                ['Unhandled' => [$exception->getMessage()]],
                $caseData['500']['data'] ?? null,
                $responseType,
                $debug
            );
        }
    }

    /**
     * Standardizes error responses for web and API.
     *
     * @template T of JsonResponse | RedirectResponse
     * @param string $message
     * @param string $route
     * @param MessageBag | array | string $errors
     * @param mixed $data
     * @param class-string<T> $responseType
     * @param bool $debug
     * @return T
     */
    private function handleErrorResponse(
        string $message,
        string $route,
        MessageBag | array | string $errors,
        mixed $data = null,
        string $responseType = RedirectResponse::class,
        bool $debug = false
    ) {
        $responseData = [
            'message' => $message,
            'success' => false
        ];

        if ($data !== null) {
            $responseData['data'] = $data;
        }

        if ($responseType === RedirectResponse::class) {
            return redirect()
                ->intended($route)
                ->with($responseData)
                ->withInput()
                ->withErrors($errors);
        }

        if (!empty($errors)) {
            $responseData['errors'] = $errors instanceof MessageBag ? $errors->toArray() : $errors;
        }

        return response()->json($responseData);
    }

    /**
     * Standardizes successful responses for web and API.
     *
     * @template T of JsonResponse | RedirectResponse
     * @param string $message
     * @param string $route
     * @param mixed $data
     * @param class-string<T> $responseType
     * @return T
     */
    private function handleSuccessResponse(
        string $message,
        string $route,
        mixed $data,
        string $responseType = RedirectResponse::class
    ) {
        $responseData = [
            'message' => $message,
            'success' => true
        ];

        if ($data !== null) {
            $responseData['data'] = $data;
        }

        if ($responseType === RedirectResponse::class) {
            return redirect()
                ->intended($route)
                ->with($responseData);
        }

        return response()->json($responseData);
    }
}
