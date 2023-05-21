<?php

namespace App\Repository;

use App\Models\User;

interface UserRepoInterface
{
    public function findByEmail(string $email): ?User;
    public function create(string $email): ?User;
}
