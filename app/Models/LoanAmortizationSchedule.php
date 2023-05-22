<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $loan_id
 * @property int|mixed $idx
 * @property mixed|string $ym
 * @property int|mixed $starting_balance
 * @property int|mixed $monthly_payment
 * @property int|mixed $principal_component
 * @property int|mixed $interest_component
 * @property int|mixed $ending_balance
 */
class LoanAmortizationSchedule extends Model
{
    protected $table = "loan_amortization_schedule";
    use HasFactory;
    use SoftDeletes;
}
