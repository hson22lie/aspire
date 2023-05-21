<?php

namespace App\Repository;

use App\Http\Requests\LoanRequest;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class LoanRepository implements LoanRepoInterface
{
    public function create(LoanRequest $loanRequest): Loan
    {
        $loan = new Loan();
        $loan->term = $loanRequest->term;
        $loan->loan_amount = $loanRequest->amount;
        $loan->user_id = $loanRequest->user_id;
        $loan->created_by = $loanRequest->user_id;
        $loan->save();
        return $loan;
    }

    public function detail(int $loanID): Loan
    {
        return Loan::where('id', $loanID)->with('detail')->first();
    }

    public function findOpenLoan(int $userID): bool
    {
        $loan = Loan::where('user_id', $userID)->where('status', 'pending')->first();
        return boolval($loan);
    }

    public function createDetail(mixed $loanDetail): LoanDetail
    {
        $detail = new LoanDetail();
        $detail->loan_id = $loanDetail['id'];
        $detail->installment_amount = $loanDetail['amount'];
        $detail->save();
        return $detail;
    }

    public function getAllTransaction(): LengthAwarePaginator
    {
        return Loan::paginate(10);
    }
    public function getTransactionByUserID(int $userID): LengthAwarePaginator
    {
        return Loan::where('user_id', $userID)->paginate(10);
    }

    public function findLoanByID(int $loanID): ?Loan
    {
        return Loan::where('id', $loanID)->first();
    }

    public function findLoanDetailByID(int $loanID): ?Collection
    {
        return LoanDetail::where('loan_id', $loanID)->get();
    }
}
