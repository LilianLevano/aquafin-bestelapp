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
     * @param Closure(Request): (T) $methodToTry Main logic that may throw
     * @param array{
     *   200: array{message: string, route: string},
     *   422: array{message: string, route: string},
     *   500: array{message: string, route: string}
     * } $data
     * @param class-string<T> $responseType Either RedirectResponse::class or JsonResponse::class (default is Redirect)
     * @param bool $debug Attach debug info to output
     * @return T
     */
    public function handleWithCases(
        Request $request,
        Closure $methodToTry,
        array $data,
        string $responseType = RedirectResponse::class,
        bool $debug = false
    ) {
        try {
            $methodToTry($request);
            return $this->handleSuccessResponse(
                $data[200],
                $responseType
            );
        } catch (ValidationException $e) {
            return $this->handleErrorResponse(
                $data[422],
                $responseType,
                ['errors' => $e->errors()],
                $debug
            );
        } catch (Throwable $e) {
            return $this->handleErrorResponse(
                $data[500],
                $responseType,
                ['exception' => $e],
                $debug
            );
        }
    }

    /**
     * Unifies the process of sanitizing many errors by removing newlines and carriage returns.
     *
     * @param mixed $errors
     * @return mixed
     */
    private function sanitizeErrors($errors)
    {
        if (is_array($errors)) {
            $new = [];
            foreach ($errors as $key => $val) {
                if (is_array($val)) {
                    $new[$key] = array_map(function ($v) {
                        return is_string($v) ? trim(preg_replace('/[\r\n]+/', ' ', $v)) : $v;
                    }, $val);
                } else {
                    $new[$key] = is_string($val) ? trim(preg_replace('/[\r\n]+/', ' ', $val)) : $val;
                }
            }
            return $new;
        }
        return $errors;
    }

    /**
     * Creates a standardized error response for web or API clients.
     *
     * @template T of JsonResponse | RedirectResponse
     * @param array{message: string, route: string} $data
     * @param class-string<T> $responseType
     * @param array $responseParameters Optional: ['errors' => array, 'exception' => Throwable]
     * @param bool $debug Attach exception info if true
     * @return T
     */
    private function handleErrorResponse(
        array $data,
        string $responseType = RedirectResponse::class,
        array $responseParameters = [],
        bool $debug = false
    ) {
        $errors = $responseParameters['errors'] ?? [];
        $exception = $responseParameters['exception'] ?? null;

        // Clean message for session: remove newlines/carriage returns.
        $sanitizedMessage = isset($data['message'])
            ? trim(preg_replace('/[\r\n]+/', ' ', $data['message']))
            : '';
        $responseData = [
            'message' => $sanitizedMessage,
            'success' => false,
        ];

        if ($responseType === RedirectResponse::class) {
            $safeErrors = $this->sanitizeErrors($errors);
            $response = redirect()
                ->intended($data['route'])
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
     * @param array{message: string, route: string} $data
     * @param class-string<T> $responseType
     * @return T
     */
    private function handleSuccessResponse(
        array $data,
        string $responseType = RedirectResponse::class
    ) {
        // Clean message for session: remove newlines/carriage returns.
        $sanitizedMessage = isset($data['message'])
            ? trim(preg_replace('/[\r\n]+/', ' ', $data['message']))
            : '';
        $responseData = [
            'message' => $sanitizedMessage,
            'success' => true,
        ];

        if ($responseType === RedirectResponse::class) {
            return redirect()
                ->intended($data['route'])
                ->with($responseData);
        }

        return response()->json($responseData);
    }
}
