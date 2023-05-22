<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('extra_repayment_schedule', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("idx");
            $table->integer('ym')->comment("yyyymm");
            $table->integer('starting_balance')->comment("시작 잔액");
            $table->integer('monthly_payment')->comment("월 지불액");
            $table->integer('principal_component')->comment("원금");
            $table->integer('interest_component')->comment("이자");

            $table->integer('ending_balance')->comment("기말 잔액");
            $table->integer('remaining_loan_term')->comment("남은 대출기간");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('extra_repayment_schedule', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_id', false)->after("id")->nullable()->comment("loan_fk");
            $table->foreign('loan_id')->references('id')->on('loans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_repayment_schedule');
    }
};
