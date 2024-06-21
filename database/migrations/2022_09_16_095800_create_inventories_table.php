<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('purchase_id')->unsigned()->nullable();
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->bigInteger('horse_make_id')->unsigned()->nullable();
            $table->foreign('horse_make_id')->references('id')->on('horse_makes')->onDelete('cascade');
            $table->bigInteger('horse_model_id')->unsigned()->nullable();
            $table->foreign('horse_model_id')->references('id')->on('horse_models')->onDelete('cascade');
            $table->bigInteger('vehicle_make_id')->unsigned()->nullable();
            $table->foreign('vehicle_make_id')->references('id')->on('vehicle_makes')->onDelete('cascade');
            $table->bigInteger('vehicle_model_id')->unsigned()->nullable();
            $table->foreign('vehicle_model_id')->references('id')->on('vehicle_models')->onDelete('cascade');
            $table->string('inventory_number')->nullable();
            $table->string('weight')->nullable()->default(1);
            $table->string('balance')->nullable();
            $table->string('measurement')->nullable()->default('Item(s)');
            $table->string('part_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('qty')->nullable();
            $table->string('rate')->nullable();
            $table->string('value')->nullable();
            $table->string('condition')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('purchase_type')->nullable();
            $table->string('warranty_exp_date')->nullable();
            $table->string('residual_value')->nullable();
            $table->string('life')->nullable();
            $table->string('life_expiration_date')->nullable();
            $table->string('depreciation_value')->nullable();
            $table->string('depreciation_percentage')->nullable();
            $table->string('mileage')->nullable();
            $table->string('depreciation_type')->nullable();
            $table->text('description')->nullable();
            $table->text('filename')->nullable();
            $table->string('status')->nullable()->default(1);
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
        Schema::dropIfExists('inventories');
    }
}
