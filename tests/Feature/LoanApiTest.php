<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoanApiTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // set your headers here
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getBearerToken(),
            'Accept' => 'application/json'
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetLoan()
    {
        $response = $this->get('/api/loan');
        $response->assertStatus(200);
    }

    public function testGetDetailLoan()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getBearerToken()])
            ->get('/api/loan/2');
        $response->assertStatus(200);
    }

    public function testCreateLoan()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getBearerToken()])
            ->post('/api/loan', [
                "amount" => 300,
                "term" => 3
            ]);
        $response->assertStatus(200);
    }

    public function testApproveLoan()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getBearerTokenAdmin()])
            ->patch('/api/admin/loan/1/approval', [
                "status" => "approved",
                "payment_date" => "2022-12-05"
            ]);
        $response->assertStatus(200);
    }

    public function testRepaymentLoan()
    {
        $user = new User();
        $user->id = 1;
        $user->email = "tester";
        $user->role = User::ROLE_USER;
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patch('/api/loan/1/repayment', [
                "amount" => "100",
            ]);
        $response->assertStatus(200);
    }


    private function getBearerToken()
    {
        $user = new User();
        $user->id = 2;
        $user->email = "tester";
        $user->role = User::ROLE_USER;
        return JWTAuth::fromUser($user);
    }

    private function getBearerTokenAdmin()
    {
        $user = new User();
        $user->id = 1;
        $user->email = "admin@aspire.com";
        $user->role = User::ROLE_ADMIN;
        return JWTAuth::fromUser($user);
    }

}
