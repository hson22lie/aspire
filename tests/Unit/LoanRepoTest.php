<?php

namespace Tests\Unit;

use App\Http\Requests\LoanRequest;
use App\Models\LoanDetail;
use App\Repository\LoanRepoInterface;
use App\Repository\LoanRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoanRepoTest extends TestCase
{
    use DatabaseTransactions;

    private LoanRepoInterface $loanRepo;

    public function setUp(): void
    {
        parent::setUp();
        $this->loanRepo = new LoanRepository();
        // Do your extra thing here
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateLoan()
    {
        $loanRequest = new LoanRequest();
        $loanRequest->setMethod('POST');
        $loanRequest->request->add([
            "amount" => 100,
            "term" => 3
        ]);
        $loanRequest->user_id = 2;
        $result = $this->loanRepo->create($loanRequest);
        $this->assertIsInt($result->id);
    }

    public function testDetail()
    {
        $loanID = 1;
        $result = $this->loanRepo->findLoanByID(1);
        $this->assertEquals($loanID, $result->id);
    }

    public function testFindOpenLoan()
    {
        $loanID = 1;
        $result = $this->loanRepo->findOpenLoan($loanID);
        $this->assertTrue($result);
    }

    public function testFailedOpenLoan()
    {
        $loanID = 2;
        $result = $this->loanRepo->findOpenLoan($loanID);
        $this->assertFalse($result);
    }

    public function testCreateLoanDetail()
    {
        $loanDetail = [
            'id' => 5,
            'amount' => 100
        ];
        $result = $this->loanRepo->createDetail($loanDetail);
        $this->assertEquals($loanDetail['amount'], $result->installment_amount);
    }

    public function testGetTransactionByUserId()
    {
        $userID = 1;
        $result = $this->loanRepo->getTransactionByUserID($userID);
        $this->assertEquals(1, $result->total());
        $userID = 2;
        $result = $this->loanRepo->getTransactionByUserID($userID);
        $this->assertEquals(1, $result->total());
    }

    public function testGetAllTransaction()
    {
        $result = $this->loanRepo->getAllTransaction();
        $this->assertEquals(2, $result->total());
    }

    public function testgetDetailTransaction()
    {
        $orderID = 1;
        $result = $this->loanRepo->detail($orderID);
        $this->assertEquals(3, count($result->detail));
    }

    public function testFindLoanDetailByID()
    {
        $orderID = 1;
        $result = $this->loanRepo->findLoanDetailByID($orderID);
        $this->assertEquals(3, count($result));
    }

    public function testFindUnpaidDetailByLoanID()
    {
        $orderID = 2;
        $result = $this->loanRepo->findUnpaidDetailByLoanID($orderID);
        $this->assertInstanceOf(LoanDetail::class, $result);
    }
}
