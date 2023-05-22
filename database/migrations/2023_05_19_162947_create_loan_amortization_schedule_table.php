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
        Schema::create('loan_amortization_schedule', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("idx");
            $table->integer('ym')->comment("yyyymm");
            $table->integer('starting_balance')->comment("starting balance");
            $table->integer('monthly_payment')->comment("monthly payment");
            $table->integer('principal_component')->comment("principal component");
            $table->integer('interest_component')->comment("interest component");

            $table->integer('ending_balance')->comment("ending balance");
            $table->timestamps();
        });

        Schema::table('loan_amortization_schedule', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_id', false)->after("id")->nullable()->comment("loan_fk");
            $table->foreign('loan_id')->references('id')->on('loans');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_amortization_schedule', function (Blueprint $table) {
            $table->dropForeign(['loan_id']);
            $table->dropColumn('loan_id');
        });

        Schema::dropIfExists('loan_amortization_schedule');
    }
};
