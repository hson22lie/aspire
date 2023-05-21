<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthInterface $authInterface;

    public function __construct(
        AuthInterface $authInterface
    ) {
        $this->authInterface = $authInterface;
    }

    public function generateToken(Request $request): JsonResponse
    {
        try {
            $user = $this->authInterface->generateToken($request->get('email'));
            return $this->successResponse("success", ['token' => $user]);
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function loginAdmin(Request $request): JsonResponse
    {
        try {
            return $this->successResponse(
                "success",
                ['token' => $this->authInterface->validateAdmin($request->all())]
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}
