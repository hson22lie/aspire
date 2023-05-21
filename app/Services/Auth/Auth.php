<?php

namespace App\Services\Auth;

use App\Repository\UserRepoInterface;
use App\Services\Auth\AuthInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class Auth implements AuthInterface
{
    private UserRepoInterface $userRepo;

    public function __construct(
        UserRepoInterface $userRepoInterface,
    ) {
        $this->userRepo = $userRepoInterface;
    }
    public function generateToken(string $email): string
    {
        $user = $this->userRepo->findByEmail($email) ?? $this->userRepo->create($email);
        $token = JWTAuth::fromUser($user);
        return $token;
    }
}
