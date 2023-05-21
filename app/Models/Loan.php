<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    public const PENDING = "pending";
    public const APPROVED = "approved";
    public const REJECTED = "rejected";
    public const PAID = "paid";

    protected $fillable     = [
        'user_id',
        'loan_amount',
        'term',
        'status',
        'approved_by',
        'approved_at',
        'disbursed_at',
        'paid_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
    public function detail()
    {
        return $this->hasMany(LoanDetail::class, 'loan_id', 'id');
    }
}
