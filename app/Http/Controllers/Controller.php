<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanSubmitRequest;
use App\Models\Loan;
use App\Models\LoanAmortizationSchedule;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index() {
        return view("index", [] );
    }

    public function submit( LoanSubmitRequest $req ) {
        print_r( $req->all() );

        $inputs = $req->only( [ "amount", "rate", "term", "extra_payment" ] );
        print_r( $inputs );

        try {
            DB::beginTransaction();
            // loan
            $loan = new Loan();
            $loan->fill( $inputs );
            $loan->save();

            $this->createSchedule( $loan );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug( "add Loan Exception", [ $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode() ] );
        }

    }


    private function createSchedule( Loan $loan ) {
        // 시작일자 획득
        $now = Carbon::now();

        $monthlyPrincipalComponent = $loan->amount / $loan->getMonthlyTerm();
        $monthlyInterestComponent = ( $loan->amount * ( $loan->rate / 100 ) * $loan->term ) / 12;
        // 월 기간 루프 정보 획득 via term
        for ( $ii = 1 ; $ii <= $loan->getMonthlyTerm() ; $ii++ ) {

            // 월이율
            $monthlyRate = ( $loan->rate / 12 ) / 100;

            // 월 지급액
            $monthlyPayment = ( $loan->amount * $monthlyRate ) / ( 1 - ( 1 + $monthlyRate * -12 ) );

            "<br />";
            echo "amount {$loan->amount} <br />";
            echo "monthlyRate {$monthlyRate} <br />";
            echo "monthlyRate * -ii " . ($monthlyRate * -$ii) . "<br />";
            echo "monthlyPayment {$monthlyPayment} <br />";

            // 월 지급 원액
            $principalComponent = $monthlyPrincipalComponent * $ii;
            echo "principalComponent {$principalComponent} <br />";

            //  월단위 이자계산 : 원금 x 연이율 x 월수 /12
            $interestComponent = $monthlyInterestComponent * $ii;


            $loanSchedule = new LoanAmortizationSchedule();
            $loanSchedule->loan_id = $loan->id;
            $loanSchedule->idx = $ii;
            $loanSchedule->ym = $now->addMonth()->format("Ym");
            $loanSchedule->starting_balance = $loan->amount - ($monthlyPrincipalComponent * ( $ii - 1 ) ) ;        // TODO
            $loanSchedule->monthly_payment = $principalComponent + $interestComponent;
            $loanSchedule->principal_component = $principalComponent;
            $loanSchedule->interest_component = $interestComponent;
            $loanSchedule->ending_balance = $loan->amount - $principalComponent;
            $loanSchedule->save();
        }

    }

}
