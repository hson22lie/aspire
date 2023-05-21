<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthInterface $authInterface;

    public function __construct(
        AuthInterface $authInterface
    ) {
        $this->authInterface = $authInterface;
    }
    public function generateToken(Request $request): array
    {
        $user = $this->authInterface->generateToken($request->get('email'));
        return ['token' => $user];
    }
}
