<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->bigInteger('category_value_id')->unsigned()->nullable();
            $table->bigInteger('purchase_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('vendor_type_id')->unsigned()->nullable();
            $table->foreign('vendor_type_id')->references('id')->on('vendor_types')->onDelete('cascade');
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->string('asset_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('qty')->nullable();
            $table->string('rate')->nullable();
            $table->string('value')->nullable();
            $table->string('contents')->nullable()->default(1);
            $table->string('balance')->nullable();
            $table->string('measurement')->nullable()->nullable();
            $table->string('condition')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('purchase_type')->nullable();
            $table->string('warranty_exp_date')->nullable();
            $table->string('residual_value')->nullable();
            $table->string('life')->nullable();
            $table->string('life_expiration_date')->nullable();
            $table->string('depreciation_value')->nullable();
            $table->string('depreciation_percentage')->nullable();
            $table->string('depreciation_type')->nullable();
            $table->text('description')->nullable();
            $table->text('filename')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('assets');
    }
}
