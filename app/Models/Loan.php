<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property numeric $term
 * @property mixed $id
 * @property mixed $rate
 * @property mixed $amount
 * @property float|int|mixed $effective_interest_rate
 * @property mixed $repayment_amount
 * @property mixed $loanAmortizationSchedules
 * @property mixed $extraRepaymentSchedules
 */
class Loan extends Model
{
    use HasFactory;

    protected $fillable = [ "amount", "rate", "term", "extra_payment" ];

    public function getMonthlyTerm() {
        return $this->term * 12;
    }

    public function repayments() {
        return $this->hasMany(Repayment::class);
    }

    public function repaymentCount() {
        return $this->hasMany(Repayment::class)->count();
    }

    public function repaymentSumup() {
        return $this->hasMany(Repayment::class)->select("repayment")->sum("repayment");
    }

    public function loanAmortizationSchedules(): HasMany
    {
        return $this->hasMany( LoanAmortizationSchedule::class );
    }

    public function extraRepaymentSchedules(): HasMany
    {
        return $this->hasMany( ExtraRepaymentSchedule::class );
    }

    public function schedules() {
        return $this->repayment_amount > 0 ? $this->extraRepaymentSchedules : $this->loanAmortizationSchedules;
    }
}
