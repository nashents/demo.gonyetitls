<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('salary_id')->unsigned()->nullable();
            $table->foreign('salary_id')->references('id')->on('salaries')->onDelete('cascade');
            $table->bigInteger('salary_item_id')->unsigned()->nullable();
            $table->foreign('salary_item_id')->references('id')->on('salary_items')->onDelete('cascade');
            $table->string('amount')->nullable();
            $table->bigInteger('loan_id')->unsigned()->nullable();
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->string('loan_amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_details');
    }
}
