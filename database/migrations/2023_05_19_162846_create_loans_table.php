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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->integer( "amount")->comment("대출금액");
            $table->string( "rate")->comment("년 이자율");
            $table->string( "term")->comment("대출금액");
            $table->string( "extra_payment")->nullable()->comment("매월 고정 추가 지불금액");
            $table->string( "effective_interest_rate")->nullable()->comment("복리를 고려한 유효 월 이자율");
            // REF : https://www.google.com/search?q=effective+interest+rate+%EA%B5%AC%ED%95%98%EB%8A%94+%EB%B0%A9%EB%B2%95&oq=effective+interest+rate+%EA%B5%AC%ED%95%98%EB%8A%94&aqs=chrome.1.69i57j33i160l3.4745j0j7&sourceid=chrome&ie=UTF-8
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
