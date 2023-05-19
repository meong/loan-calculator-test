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
            $table->integer('ym', 6)->comment("yyyymm");
            $table->integer('ym', 6)->comment("yyyymm");
            $table->integer('ym', 6)->comment("yyyymm");
            $table->timestamps();
        });

        Schema::table('loan_amortization_schedule', function (Blueprint $table) {
            $table->unsignedInteger('loan_id', false)->nullable()->after("id")->comment("loan_fk");
            $table->foreign('loan_id')->references('id')->on('loans');
        });

        Schema::table('loan_amortization_schedule', function (Blueprint $table) {
            $table->dropForeign(['loan_id']);
            $table->dropColumn('loan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_amortization_schedule');
    }
};
