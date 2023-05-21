<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRepoTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        // Do your extra thing here
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $userRepo =  new UserRepository();
        $result = $userRepo->create("test");
        $this->assertIsNumeric($result->id);
        $this->assertInstanceOf(User::class, $result);
    }

    public function testFindUser()
    {
        $userRepo =  new UserRepository();
        $result = $userRepo->findByEmail("tester");
        $this->assertIsNumeric($result->id);
        $this->assertEquals("tester", $result->email);
        $this->assertInstanceOf(User::class, $result);
    }
}
