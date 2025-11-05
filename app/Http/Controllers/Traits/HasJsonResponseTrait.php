<?php
declare(strict_types=1);

namespace App\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

trait HasJsonResponseTrait
{
    /**
     * @var int
     */
    protected int $statusCode = 200;

    /**
     * @return JsonResponse
     */
    public function responseWithInternalError(): JsonResponse
    {
        return $this->setStatusCode(500)
            ->responseWithMessage(__('labels.internal_server_error'));
    }

    /**
     * @return JsonResponse
     */
    public function responseWithSuccess(): JsonResponse
    {
        return $this->setStatusCode(200)
            ->responseWithMessage(__('labels.success'));
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function responseWithMessage(string $message = ''): JsonResponse
    {
        return response()->json(['message' => $message], $this->statusCode);
    }

    /**
     * @param array|null $payload
     * @return JsonResponse
     */
    public function responseWithPayload(?array $payload = []): JsonResponse
    {
        return response()->json(['data' => $payload], $this->statusCode);
    }

    /**
     * @param int $code
     * @return Controller
     */
    public function setStatusCode(int $code): Controller
    {
        $this->statusCode = $code;

        return $this;
    }
}
