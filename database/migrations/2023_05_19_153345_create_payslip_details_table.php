<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayslipDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payslip_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payslip_id')->unsigned()->nullable();
            $table->foreign('payslip_id')->references('id')->on('payslips')->onDelete('cascade');
            $table->bigInteger('salary_item_id')->unsigned()->nullable();
            $table->foreign('salary_item_id')->references('id')->on('salary_items')->onDelete('cascade');
            $table->string('amount')->nullable();
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
        Schema::dropIfExists('payslip_details');
    }
}
