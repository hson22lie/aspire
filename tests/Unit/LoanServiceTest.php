<?php

namespace Tests\Unit;

use App\Http\Requests\LoanApprovalRequest;
use App\Http\Requests\LoanRepaymentRequest;
use App\Http\Requests\LoanRequest;
use App\Models\Loan as ModelsLoan;
use App\Models\LoanDetail;
use App\Models\User;
use App\Repository\LoanRepoInterface;
use App\Repository\LoanRepository;
use App\Services\Loan\Loan;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Mockery;
use Nette\Schema\Expect;
use Tests\TestCase;

class LoanServiceTest extends TestCase
{
    use DatabaseTransactions;

    private LoanRepoInterface $loanRepo;
    private Loan $loanService;

    public function setUp(): void
    {
        parent::setUp();
        $this->loanRepo = Mockery::mock(LoanRepository::class);
        $this->loanService = new Loan($this->loanRepo);
        // Do your extra thing here
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRoleAdmin()
    {
        $loan = new Loan($this->loanRepo);
        $user = new User();
        $user->role = User::ROLE_ADMIN;
        $this->loanRepo->shouldReceive('getAllTransaction')->andReturn(new LengthAwarePaginator(1, 10, 1));
        $result = $this->loanService->get($user);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function testRoleUser()
    {
        $user = new User();
        $user->role = User::ROLE_USER;
        $user->id = 1;
        $this->loanRepo->shouldReceive('getTransactionByUserID')->andReturn(new LengthAwarePaginator(1, 10, 1));
        $result = $this->loanService->get($user);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function testCreateLoan()
    {
        $loanDeteail = new LoanDetail();
        $loan = new ModelsLoan();
        $loan->term = 3;
        $loan->loan_amount = 100;
        $this->loanRepo->shouldReceive('create')->andReturn($loan);
        $this->loanRepo->shouldReceive('createDetail')->andReturn($loanDeteail);
        $loanRequest = new LoanRequest();
        $loanRequest->setMethod('POST');
        $loanRequest->request->add([
            "amount" => 100,
            "term" => 3
        ]);
        $loanRequest->user_id = 2;
        $result = $this->loanService->create($loanRequest);
        $this->isInstanceOf(LoanRepository::class, $result);
        $this->assertEquals($loanRequest->get('amount'), $result->loan_amount);
    }

    public function testCreateLoanWithNoRemaining()
    {
        $loanDeteail = new LoanDetail();
        $loan = new ModelsLoan();
        $loan->term = 3;
        $loan->loan_amount = 110;
        $this->loanRepo->shouldReceive('create')->andReturn($loan);
        $this->loanRepo->shouldReceive('createDetail')->andReturn($loanDeteail);
        $loanRequest = new LoanRequest();
        $loanRequest->setMethod('POST');
        $loanRequest->request->add([
            "amount" => 110,
            "term" => 4
        ]);
        $loanRequest->user_id = 2;
        $result = $this->loanService->create($loanRequest);
        $this->isInstanceOf(LoanRepository::class, $result);
        $this->assertEquals($loanRequest->get('amount'), $result->loan_amount);
    }

    public function testDetailLoanWithErrorNoAccess()
    {
        $loan = new ModelsLoan();
        $user = new User();
        $user->role = User::ROLE_USER;
        $user->id = 1;
        $this->loanRepo->shouldReceive('detail')->andReturn($loan);
        $this->expectException(Exception::class);
        $this->loanService->detail(1, $user);
    }

    public function testDetailLoan()
    {
        $loan = new ModelsLoan();
        $loan->user_id = 1;
        $user = new User();
        $user->role = User::ROLE_USER;
        $user->id = 1;
        $this->loanRepo->shouldReceive('detail')->andReturn($loan);
        $result = $this->loanService->detail(1, $user);
        $this->assertInstanceOf(ModelsLoan::class, $result);
    }

    public function testRejectLoanThatNotExists()
    {
        $loanApprovalRequest = new LoanApprovalRequest();
        $loanApprovalRequest->setMethod('PATCH');
        $loanApprovalRequest->loan_id = 1;
        $loanApprovalRequest->admin_id = 1;
        $loanApprovalRequest->request->add([
            'status' => 'rejected',
            "payment_date" => "2022-10-05"
        ]);
        $this->loanRepo->shouldReceive('findLoanByID')->andReturn(null);
        $this->expectException(Exception::class);
        $this->loanService->update($loanApprovalRequest);
    }

    public function testRejectLoan()
    {
        $loan = new ModelsLoan();
        $loan->status = ModelsLoan::PENDING;
        $loan->user_id = 2;
        $loan->loan_amount = 100;
        $loan->term = 2;
        $loan->created_by = 2;
        $loanApprovalRequest = new LoanApprovalRequest();
        $loanApprovalRequest->setMethod('PATCH');
        $loanApprovalRequest->loan_id = 1;
        $loanApprovalRequest->admin_id = 1;
        $loanApprovalRequest->request->add([
            'status' => 'rejected',
            "payment_date" => "2022-10-05"
        ]);
        $this->loanRepo->shouldReceive('findLoanByID')->andReturn($loan);
        $this->loanService->update($loanApprovalRequest);
        $this->assertEquals(ModelsLoan::REJECTED, $loan->status);
    }


    public function testApproveLoan()
    {
        $loan = new ModelsLoan();
        $loan->status = ModelsLoan::PENDING;
        $loan->user_id = 2;
        $loan->loan_amount = 100;
        $loan->term = 1;
        $loan->created_by = 2;
        $loan->id = 100;
        $loanApprovalRequest = new LoanApprovalRequest();
        $loanApprovalRequest->setMethod('PATCH');
        $loanApprovalRequest->loan_id = 1;
        $loanApprovalRequest->admin_id = 1;
        $loanApprovalRequest->request->add([
            'status' => 'approved',
            "payment_date" => "2022-10-05"
        ]);
        $loanDeteail = new LoanDetail();
        $loanDeteail->loan_id = $loan->id;
        $loanDeteail->installment_amount = $loan->loan_amount;
        $loanDeteail->overdue_at = "2022-10-12 00:00:00";
        $this->loanRepo->shouldReceive('findLoanByID')->andReturn($loan);
        $collection = new Collection([$loanDeteail]);
        $this->loanRepo->shouldReceive('findLoanDetailByID')->andReturn($collection);
        $this->loanService->update($loanApprovalRequest);
        $this->assertEquals(ModelsLoan::APPROVED, $loan->status);
        $this->assertEquals(
            Carbon::parse($loanApprovalRequest->get('payment_date'))->addWeek()->toDateTimeString(),
            $loanDeteail->overdue_at
        );
    }

    public function testRejectLoanWithStatusNotPending()
    {
        $loan = new ModelsLoan();
        $loan->status = ModelsLoan::APPROVED;
        $loan->user_id = 2;
        $loan->loan_amount = 100;
        $loan->term = 2;
        $loan->created_by = 2;
        $loanApprovalRequest = new LoanApprovalRequest();
        $loanApprovalRequest->setMethod('PATCH');
        $loanApprovalRequest->loan_id = 1;
        $loanApprovalRequest->admin_id = 1;
        $loanApprovalRequest->request->add([
            'status' => 'rejected',
            "payment_date" => "2022-10-05"
        ]);
        $this->loanRepo->shouldReceive('findLoanByID')->andReturn($loan);
        $this->expectException(Exception::class);
        $this->loanService->update($loanApprovalRequest);
    }

    public function testPaidRepaymentFailedNoFound()
    {
        $loanRequest = new LoanRepaymentRequest();
        $loanRequest->setMethod('PATCH');
        $loanRequest->request->add([
            "amount" => 100,
        ]);
        $loanRequest->user_id = 3;
        $loanModel = new ModelsLoan();
        $loanModel->status = ModelsLoan::PAID;
        $loanModel->user_id = 2;
        $loanRequest->loan_id = 2;
        $this->loanRepo->shouldReceive('findLoanByID')->andReturn($loanModel);
        $this->expectException(Exception::class);
        $this->loanService->repayment($loanRequest);
    }

    public function testPaidRepayment()
    {
        $loanRequest = new LoanRepaymentRequest();
        $loanRequest->setMethod('PATCH');
        $loanRequest->request->add([
            "amount" => 100,
        ]);
        $loanRequest->user_id = 2;
        $loanModel = new ModelsLoan();
        $loanModel->status = ModelsLoan::PENDING;
        $loanModel->user_id = 2;
        $loanRequest->loan_id = 2;
        $loanDetail = new LoanDetail();
        $loanDetail->loan_id = 2;
        $loanDetail->installment_amount = 100;
        $loanDetail->paid_amount = 100;
        $loanDetail->status = ModelsLoan::PENDING;
        $this->loanRepo->shouldReceive('findLoanByID')->andReturn($loanModel);
        $this->loanRepo->shouldReceive('findUnpaidDetailByLoanID')->andReturn($loanDetail);
        $this->loanService->repayment($loanRequest);
        $this->assertEquals($loanRequest->amount, $loanDetail->paid_amount);
        $this->assertEquals($loanDetail->status, ModelsLoan::PAID);
    }

    public function testPaidRepaymentAllPaid()
    {
        $loanRequest = new LoanRepaymentRequest();
        $loanRequest->setMethod('PATCH');
        $loanRequest->request->add([
            "amount" => 10,
        ]);
        $loanRequest->user_id = 2;
        $loanModel = new ModelsLoan();
        $loanModel->status = ModelsLoan::PENDING;
        $loanModel->user_id = 2;
        $loanRequest->loan_id = 2;
        $loanDetail = new LoanDetail();
        $loanDetail->loan_id = 2;
        $loanDetail->installment_amount = 100;
        $loanDetail->paid_amount = 100;
        $loanDetail->status = ModelsLoan::PENDING;
        $this->loanRepo->shouldReceive('findLoanByID')->andReturn($loanModel);
        $this->loanRepo->shouldReceive('findUnpaidDetailByLoanID')->andReturn($loanDetail);
        $this->expectException(Exception::class);
        $this->loanService->repayment($loanRequest);
    }
}
