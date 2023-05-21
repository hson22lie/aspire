<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->float('loan_amount');
            $table->tinyInteger('term');
            $table->enum('status', ['pending','approved','paid',"rejected"])->default('pending');
            $table->integer('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('disbursed_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans_tablee');
    }
};
