<?php

namespace App\Services\Auth;

use App\Repository\UserRepoInterface;
use App\Services\Auth\AuthInterface;
use Exception;
use Illuminate\Support\Facades\Hash;
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

    public function validateAdmin($credential): string
    {
        $user = $this->userRepo->findByEmail($credential['email']);
        // data not found in db, usually soft delete
        if (empty($user)) {
            throw new Exception(
                "User not found",
                422,
            );
        }

        // password is not match
        if (!Hash::check($credential['password'], $user->password)) {
            throw new Exception(
                "incorect password",
                422
            );
        }
        return JWTAuth::fromUser($user);
    }
}
