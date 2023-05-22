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
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("idx");
            $table->integer( "repayment")->comment("추가 지불금액(extra repayments)");
            $table->timestamps();
        });

        Schema::table('repayments', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_id', false)->after("id")->nullable()->comment("loan_fk");
            $table->foreign('loan_id')->references('id')->on('loans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
