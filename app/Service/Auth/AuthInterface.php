<?php

namespace App\Services\Auth;

interface AuthInterface
{
    public function generateToken(string $email): string;
}
