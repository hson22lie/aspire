<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repository\UserRepoInterface;
use App\Repository\UserRepository;
use App\Services\Auth\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;


class AuthServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
    */
    public function testGenerateTokenUser()
    {
        $userRepo = Mockery::mock(UserRepository::class);
        $auth = new Auth(
            $userRepo
        );
        $newUser = new User();
        $newUser->email = "test";
        $newUser->id = 1;
        $userRepo->shouldReceive('findByEmail')->andReturn($newUser);
        $jwt = $auth->generateToken("test");
        $this->assertIsString($jwt);
    }

    public function testGenerateTokenAdminWithWrongPassword()
    {
        $credential = [
            "email" => "admin@aspire.com",
            "password" => "12345"
        ];
        $userRepo = Mockery::mock(UserRepository::class);
        $auth = new Auth(
            $userRepo
        );
        $newUser = new User();
        $newUser->id = 1;
        $newUser->email = $credential['email'];
        $newUser->password = Hash::make("qweqeqwe");
        $userRepo->shouldReceive('findByEmail')->andReturn($newUser);
        $this->expectException(Exception::class);
        $jwt = $auth->validateAdmin($credential);
    }

    public function testGenerateTokenAdminWithCorrectPassword()
    {
        $credential = [
            "email" => "admin@aspire.com",
            "password" => "12345"
        ];
        $userRepo = Mockery::mock(UserRepository::class);
        $auth = new Auth(
            $userRepo
        );
        $newUser = new User();
        $newUser->id = 1;
        $newUser->email = $credential['email'];
        $newUser->password = Hash::make("12345");
        $userRepo->shouldReceive('findByEmail')->andReturn($newUser);
        $jwt = $auth->validateAdmin($credential);
        $this->assertIsString($jwt);
    }

    public function testGenerateTokenAdminWithUserNotFound()
    {
        $credential = [
            "email" => "admin@aspire.com",
            "password" => "12345"
        ];
        $userRepo = Mockery::mock(UserRepository::class);
        $auth = new Auth(
            $userRepo
        );
        $userRepo->shouldReceive('findByEmail')->andReturn(null);
        $this->expectException(Exception::class);
        $jwt = $auth->validateAdmin($credential);
    }
}
