<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repository\UserRepoInterface;
use App\Repository\UserRepository;
use App\Services\Auth\Auth;
use Mockery;
use Tests\TestCase;


class AuthServiceTest extends TestCase
{


    // protected function mock(
    //     string $class,
    //     $superclass = null,
    //     $params = [],
    //     $config = []
    // ) {
    //     $mock = null;
    //     if ($superclass === null) {
    //         $mock = Mockery::mock($class);
    //     } else {
    //         $mock = Mockery::mock($class, $superclass);
    //     }

    //     return $mock;
    // }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
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
}
