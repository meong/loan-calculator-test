<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtraRepaymentSchedule extends LoanAmortizationSchedule
{
    protected $table = "extra_repayment_schedule";
    use HasFactory;
    use SoftDeletes;
}
