<?php

namespace App\Repository;

use App\Models\User;

class UserRepository implements UserRepoInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(string $email): ?User
    {
        $user = new User();
        $user->email = $email;
        $user->save();
        return $user;
    }
}
