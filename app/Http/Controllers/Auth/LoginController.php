<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function loginViaEmail(LoginRequest $request): JsonResponse
    {
        if (!$token = auth('api')->attempt($request->validated())) {
            return $this->responseWithMessage(__('auth.failed'));
        }

        return $this->responseWithPayload(['token' => $token]);
    }
}
