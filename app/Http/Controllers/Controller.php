<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRepaymentSubmit;
use App\Http\Requests\LoanSubmitRequest;
use App\Models\Loan;
use App\Models\LoanAmortizationSchedule;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $loans = Loan::orderBy("created_at")->paginate();
        return view("index", [ "loans" => $loans ] );
    }

    public function create() {
        return view("create", [] );
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

            // 유효이자율 : 원금에 월 고정 추가 지불 금액을 뺀 유효 이자율 계산
            // REF : https://m.blog.naver.com/ckdrl_ckdrl/221982186150
            // 8% 반기별 복리이자인 경우 8%는 명목이자율이고 유효이자율은 (1+0.08/2)²-1=0.0816 즉, 8.16%가 된다.
            $month_term = ( $loan->term * 12 );
            Log::debug( ["month_term", $month_term]);
            $effective_interest_rate = ( pow( 1 + ($loan->rate/100) / $loan->term, $loan->term ) - 1 ) * 100;
            $loan->effective_interest_rate = $effective_interest_rate;
            $loan->save();

            $this->createSchedule( $loan );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug( "add Loan Exception", [ $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode() ] );
        }

    }

    public function repayment( Request $request, Loan $loan ) {
        return view("repayment", [ "loan" => $loan ] );
    }

    public function repaymentSubmit( LoanRepaymentSubmit $request, Loan $loan ) {

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

            // 월 지급 원액
            $principalComponent = $monthlyPrincipalComponent * $ii;

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
