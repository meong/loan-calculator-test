<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property numeric $term
 * @property mixed $id
 * @property mixed $rate
 * @property mixed $amount
 */
class Loan extends Model
{
    use HasFactory;

    protected $fillable = [ "amount", "rate", "term", "extra_payment" ];

    public function getMonthlyTerm() {
        return $this->term * 12;
    }
}
