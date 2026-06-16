<?php

namespace App\Http\Controllers;

use Closure;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

/**
 * Base controller for the application.
 * Provides shared functionality to other controllers.
 */
abstract class Controller
{
    /**
     * Handles a controller action which may result in a redirect or JSON response, standardizing structure and error handling.
     *
     * Example usage:
     *   $this->handleWithCases($request, fn ($request) => ..., [200 => [...], 422 => [...], 500 => [...]], JsonResponse::class);
     *
     * @template T of JsonResponse | RedirectResponse
     * @param Request $request
     * @param Closure(Request): (T) $methodToTry Main logic that may throw, result will be returned unless an exception is thrown
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
            $caseData['200']['data'] = $methodToTry($request);
            return $this->handleSuccessResponse(
                $caseData['200']['message'],
                $caseData['200']['route'] ?? url()->current(),
                $caseData['200']['data'] ?? null,
                $responseType
            );
        } catch (ValidationException $e) {
            return $this->handleErrorResponse(
                $caseData['422']['message'],
                $caseData['422']['route'] ?? url()->current(),
                $caseData['422']['data'] ?? null,
                $responseType,
                ['errors' => $e->errors()],
                $debug
            );
        } catch (Throwable $e) {
            return $this->handleErrorResponse(
                $caseData['500']['message'],
                $caseData['500']['route'] ?? url()->current(),
                $caseData['500']['data'] ?? null,
                $responseType,
                ['exception' => $e],
                $debug
            );
        }
    }

    /**
     * Remove newlines and carriage returns from error arrays.
     *
     * @param mixed $errors
     * @return mixed
     */
    private function sanitizeErrors($errors)
    {
        if (is_array($errors)) {
            $clean = [];
            foreach ($errors as $key => $value) {
                if (is_array($value)) {
                    $clean[$key] = array_map(function ($v) {
                        return is_string($v) ? trim(preg_replace('/[\r\n]+/', ' ', $v)) : $v;
                    }, $value);
                } else {
                    $clean[$key] = is_string($value) ? trim(preg_replace('/[\r\n]+/', ' ', $value)) : $value;
                }
            }
            return $clean;
        }
        return $errors;
    }

    /**
     * Creates a standardized error response for web or API clients.
     *
     * @template T of JsonResponse | RedirectResponse
     * @param string $message
     * @param string $route
     * @param mixed $data
     * @param class-string<T> $responseType
     * @param array $responseParameters Optional: ['errors' => array, 'exception' => Throwable]
     * @param bool $debug Attach exception info if true
     * @return T
     */
    private function handleErrorResponse(
        string $message,
        string $route,
        mixed $data,
        string $responseType = RedirectResponse::class,
        array $responseParameters = [],
        bool $debug = false
    ) {
        $errors = $responseParameters['errors'] ?? [];
        $exception = $responseParameters['exception'] ?? null;

        // Clean message for session: remove newlines/carriage returns.
        $sanitizedMessage = isset($message)
            ? trim(preg_replace('/[\r\n]+/', ' ', $message))
            : '';
        $responseData = [
            'message' => $sanitizedMessage,
            'success' => false,
        ];

        if ($data !== null) {
            $responseData['data'] = $data;
        }

        if ($responseType === RedirectResponse::class) {
            $safeErrors = $this->sanitizeErrors($errors);
            $response = redirect()
                ->intended($route)
                ->with($responseData)
                ->withInput()
                ->withErrors($safeErrors);

            if ($debug && $exception instanceof Throwable) {
                $exceptionMessage = trim(preg_replace('/[\r\n]+/', ' ', $exception->getMessage()));
                $response->with('exception', $exceptionMessage);
            }
            return $response;
        }

        // For API/JSON
        if (!empty($errors)) {
            $responseData['errors'] = $errors; // JSON can keep newlines
        }

        if ($debug && $exception instanceof Throwable) {
            $responseData['exception'] = $exception->getMessage();
        }

        return response()->json($responseData);
    }

    /**
     * Standardizes successful responses for web or API.
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
        // Clean message for session: remove newlines/carriage returns.
        $sanitizedMessage = isset($message)
            ? trim(preg_replace('/[\r\n]+/', ' ', $message))
            : '';
        $responseData = [
            'message' => $sanitizedMessage,
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
