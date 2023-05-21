<?php

namespace App\Services\Loan;

use App\Http\Requests\LoanRequest;
use App\Repository\LoanRepoInterface;
use App\Models\Loan as LoanModel;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class Loan implements LoanInterface
{
    private LoanRepoInterface $loanRepo;

    public function __construct(
        LoanRepoInterface $loanRepoInterface
    ) {
        $this->loanRepo = $loanRepoInterface;
    }

    public function create(LoanRequest $loanRequest): LoanModel
    {
        DB::beginTransaction();
        $loan = $this->loanRepo->create($loanRequest);
        $this->createDetails($loan);
        DB::commit();
        return $loan;
    }

    public function get(User $user): LengthAwarePaginator
    {
        return ($user->role == User::ROLE_ADMIN) ? $this->loanRepo->getAllTransaction()
            : $this->loanRepo->getTransactionByUserID($user->id);
    }

    public function detail(int $loanID, User $user): LoanModel
    {
        $loan = $this->loanRepo->detail($loanID);
        if ($loan->user_id != $user->id) {
            if ($user->role != USer::ROLE_ADMIN) {
                throw new Exception("you can't access this api", 403);
            }
        }
        return $loan;
    }

    private function createDetails(LoanModel $loan)
    {
        $installments = $this->prepareInstallmentAmount($loan->loan_amount, $loan->term);
        foreach ($installments as $installment) {
            $params = [
                'id' => $loan->id,
                'amount' => $installment,
            ];
            $this->loanRepo->createDetail($params);
        }
    }

    private function prepareInstallmentAmount($amount, $term)
    {
        $installment = round($amount / $term, 2);
        $arr = [];
        while ($amount != 0) {
            if ($amount >= $installment) {
                $arr[] = $installment;
                $amount -= $installment;
            } elseif ($amount < 1) {
                $arr[count($arr) - 1 ] += $amount;
                $amount = 0;
            } else {
                $arr[] = $amount;
                $amount = 0;
            }
        }
        return $arr;
    }
}
