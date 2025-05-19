<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class Controller
{
    /**
     * Универсальный обработчик для выполнения бизнес-логики с перехватом исключений.
     *
     * Обрабатывает:
     * - NotFoundHttpException → возвращает статус 404 с сообщением
     * - ConflictHttpException → возвращает статус 409 с сообщением
     * - По умолчанию — возвращает результат выполнения коллбэка с указанным кодом (по умолчанию 200)
     *
     * @param callable $callback Бизнес-логика, которую нужно безопасно выполнить
     * @param int $successCode Код, возвращаемый при успешном выполнении (по умолчанию 200)
     * @return JsonResponse
     */
    protected function handleWithExceptions(callable $callback, int $successCode = 200): JsonResponse
    {
        try {
            $result = $callback();

            // Если результат уже является JsonResponse, просто возвращаем его
            if ($result instanceof JsonResponse) {
                return $result;
            }

            // Иначе — формируем стандартный ответ
            return response()->json($result, $successCode);

        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (ConflictHttpException $e) {
            return response()->json(['error' => $e->getMessage()], 409);
        }
    }
}
