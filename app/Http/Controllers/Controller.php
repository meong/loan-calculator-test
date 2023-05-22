<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRepaymentSubmit;
use App\Http\Requests\LoanSubmitRequest;
use App\Models\ExtraRepaymentSchedule;
use App\Models\Loan;
use App\Models\LoanAmortizationSchedule;
use App\Models\Repayment;
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

    public function submit( LoanSubmitRequest $request ) {
        $inputs = $request->only( [ "amount", "rate", "term", "extra_payment" ] );

        try {
            DB::beginTransaction();
            // loan
            $loan = new Loan();
            $loan->fill( $inputs );

            // 유효이자율 : 원금에 월 고정 추가 지불 금액을 뺀 유효 이자율 계산
            // REF : https://m.blog.naver.com/ckdrl_ckdrl/221982186150
            // 8% 반기별 복리이자인 경우 8%는 명목이자율이고 유효이자율은 (1+0.08/2)²-1=0.0816 즉, 8.16%가 된다.
            $month_term = ( $loan->term * 12 );
            Log::debug( ["month_term", $month_term] );
            $effective_interest_rate = ( pow( 1 + ($loan->rate/100) / $loan->term, $loan->term ) - 1 ) * 100;
            $loan->effective_interest_rate = $effective_interest_rate;
            $loan->save();

            $this->createSchedule( $loan );

            DB::commit();

            return redirect()->route('index')->with("success", "성공적으로 처리되었습니다." );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug( "add Loan Exception", [ $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode() ] );
        }

    }

    public function schedules( Request $request, Loan $loan ) {

        return view("schedules", [ "loan" => $loan ] );
    }

    public function repayment( Request $request, Loan $loan ) {
        return view("repayment", [ "loan" => $loan ] );
    }

    public function repaymentSubmit( LoanRepaymentSubmit $request, Loan $loan ) {
        $extra_repayments = $request->input( "extra_repayments" );
        try {
            DB::beginTransaction();

            $repayment = new Repayment();
            $repayment->loan_id = $loan->id;
            $repayment->idx = $loan->repaymentCount() + 1;
            $repayment->repayment = $extra_repayments;
            $repayment->save();

            // 총 추가지불금액 합계
            $repaymentAmount = $loan->repaymentSumup();
            $loan->repayment_amount = $repaymentAmount;
            $loan->save();

            // 기존 스케쥴의 첫번째 스케쥴 일정을 획득. 기존 일정을 기준으로 리스케쥴링 한다.
            $firstSchedule = LoanAmortizationSchedule::where("loan_id", $loan->id)->orderBy("idx")->first();

            $this->createSchedule( $loan, $firstSchedule );

            DB::commit();

            return redirect()->route('index')->with("success", "성공적으로 처리되었습니다." );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug( "add Loan Exception", [ $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode() ] );
        }

    }


    private function createSchedule( Loan $loan, $firstSchedule = null ) {

        $isRepayment = ( $loan->repayment_amount > 0 && $firstSchedule );

        // 시작일자 획득
        $now = Carbon::now();

        $monthlyPrincipalComponent = $loan->amount / $loan->getMonthlyTerm();
        $monthlyInterestComponent = ( $loan->amount * ( $loan->rate / 100 ) * $loan->term ) / 12;

        // repayment 인 경우 기존 스케쥴 삭제
        $loan->extraRepaymentSchedules()->delete();

        // 월 기간 루프 정보 획득 via term
        for ( $ii = 1 ; $ii <= $loan->getMonthlyTerm() ; $ii++ ) {

//      문서의 수식대로 계산을 하는 경우 기대하는 값이 나오지 않아, 별도로 이해한 로직으로 구현. 해당 내용은 주석.
//            // 월이율
//            $monthlyRate = ( $loan->rate / 12 ) / 100;
//            // 월 지급액
//            $monthlyPayment = ( $loan->amount * $monthlyRate ) / ( 1 - ( 1 + $monthlyRate * -12 ) );

            // 월 지급 원액
            $principalComponent = $monthlyPrincipalComponent * $ii;

            //  월단위 이자계산 : 원금 x 연이율 x 월수 /12
            $interestComponent = $monthlyInterestComponent * $ii;

            if( $isRepayment ) {
                $loanSchedule = new ExtraRepaymentSchedule();
            } else {
                $loanSchedule = new LoanAmortizationSchedule();
            }

            $loanSchedule->loan_id = $loan->id;
            $loanSchedule->idx = $ii;
            $loanSchedule->ym = $now->addMonth()->format("Ym");
            $loanSchedule->starting_balance = $loan->amount - ($monthlyPrincipalComponent * ( $ii - 1 ) ) ;        // TODO
            $loanSchedule->monthly_payment = $principalComponent + $interestComponent;
            $loanSchedule->principal_component = $principalComponent;
            $loanSchedule->interest_component = $interestComponent;
            Log::debug( [ "loanSchedule->ending_balance", $loan->amount, $principalComponent, $loan->extra_payment, $loan->repayment_amount ] );
            $loanSchedule->ending_balance = $loan->amount - ( $principalComponent + ( $loan->extra_payment * $ii ) + $loan->repayment_amount );

            // TODO : 남은 대출 기간 정보 획득 필요
            if( $isRepayment ) {
                $loanSchedule->remaining_loan_term = 0;
            }

            if( $loanSchedule->ending_balance <= 0 ) {
                // TODO : 마지막 달 스케쥴에 금액이 오버하는 경우 0원에 맞추어 이자율과 함께 계산 필요
            }

            $loanSchedule->save();

            if( $loanSchedule->ending_balance <= 0 ) break;
        }

    }

}
