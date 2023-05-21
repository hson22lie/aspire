<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanApprovalRequest;
use App\Http\Requests\LoanRequest;
use App\Services\Loan\LoanInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoanController extends Controller
{
    private LoanInterface $loanService;
    public function __construct(
        LoanInterface $loanInterface
    ) {
        $this->loanService = $loanInterface;
    }

    public function get(): JsonResponse
    {
        try {
            $transaction = $this->loanService->get(auth('api')->user());
            return $this->successResponse("success", [$transaction]);
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function detail(int $loanID): JsonResponse
    {
        $detail = $this->loanService->detail($loanID, auth('api')->user());
        return $this->successResponse("", [$detail]);
    }

    public function create(LoanRequest $loanRequest): JsonResponse
    {
        try {
            $loanRequest->user_id = auth('api')->user()->id;
            return $this->successResponse(
                "success",
                [$this->loanService->create($loanRequest)],
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function updateLoanApproval($loanID, LoanApprovalRequest $loanApprovalRequest)
    {
        try {
            $loanApprovalRequest->admin_id = auth('api')->user()->id;
            $loanApprovalRequest->loan_id = $loanID;
            $this->loanService->update($loanApprovalRequest);
            $msg = sprintf("success patch loan ID : %d", $loanID);
            return $this->successResponse($msg);
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}
