<?php

namespace App\Services\Auth;

interface AuthInterface
{
    public function generateToken(string $email): string;
    public function validateAdmin($credential): string;
}
