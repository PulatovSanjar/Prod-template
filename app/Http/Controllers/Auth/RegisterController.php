<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Services\RegisterService;
use Illuminate\Http\JsonResponse;
use App\Http\DTOs\RegisterUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;

class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        /** @var RegisterService $registerService */
        $registerService = resolve(RegisterService::class);
        $data = RegisterUserDTO::transform($request->validated());

        $registerService->register($data);

        return $this->responseWithSuccess();
    }

    /**
     * @param VerifyEmailRequest $request
     * @return JsonResponse
     */
    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        /** @var RegisterService $registerService */
        $registerService = resolve(RegisterService::class);

        $registerService->verifyEmail($request->get('token'));

        return $this->responseWithSuccess();
    }
}
