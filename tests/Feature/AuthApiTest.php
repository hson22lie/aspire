<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserLogin()
    {

        $response = $this->post('/api/token', ['email' => 'test22']);
        $response->assertStatus(200);
        $token = $response->decodeResponseJson()['data']['token'];
        $this->assertIsString($token);
    }

    public function testUserLoginFailed()
    {

        $response = $this->post('/api/token', ['email' => '']);
        $response->assertStatus(500);
        $token = $response->decodeResponseJson()['data']['message'];
        $this->assertIsString($token);
    }

    public function testAdminLogin()
    {

        $response = $this->post('/api/admin/login', ['email' => 'admin@aspire.com', 'password' => 'password']);
        $response->assertStatus(200);
        $token = $response->decodeResponseJson()['data']['token'];
        $this->assertIsString($token);
    }

    public function testAdminLoginFailedPassword()
    {

        $response = $this->post('/api/admin/login', ['email' => 'admin@aspire.com', 'password' => 'passwords']);
        $response->assertStatus(422);
        $errMsg = $response->decodeResponseJson()['message'];
        $this->assertEquals("incorect password", $errMsg);
    }
}
