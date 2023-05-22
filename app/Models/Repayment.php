<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $loan_id
 * @property mixed $repayment
 */
class Repayment extends Model
{
    use HasFactory;

    public function loan() {
        return $this->belongsTo( Loan::class );
    }
}
