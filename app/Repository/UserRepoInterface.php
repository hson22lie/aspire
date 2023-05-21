<?php

namespace App\Repository;

use App\Models\User;

interface UserRepoInterface
{
    public function findByEmail(string $email): ?User
}
