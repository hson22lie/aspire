<?php

namespace App\Repository;

use App\Http\Requests\LoanRequest;
use App\Models\Loan;
use App\Models\LoanDetail;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LoanRepoInterface
{
    public function create(LoanRequest $loanRequest): Loan;
    public function detail(int $loanID): Loan;
    public function findOpenLoan(int $userID): bool;
    public function createDetail(mixed $loanDetail): LoanDetail;
    public function getAllTransaction(): LengthAwarePaginator;
    public function getTransactionByUserID(int $userID): LengthAwarePaginator;
}
