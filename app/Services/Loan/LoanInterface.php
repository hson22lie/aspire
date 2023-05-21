<?php

namespace App\Services\Loan;

use App\Http\Requests\LoanRequest;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LoanInterface
{
    public function create(LoanRequest $loanRequest): Loan;
    public function detail(int $loanID, User $user): Loan;
    public function get(User $user): LengthAwarePaginator;
}