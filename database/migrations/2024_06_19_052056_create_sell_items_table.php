<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sell_id')->unsigned()->nullable();
            $table->foreign('sell_id')->references('id')->on('sells')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->bigInteger('tax_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('qty')->nullable();
            $table->string('tax_rate')->nullable();
            $table->string('tax_amount')->nullable();
            $table->string('amount')->nullable();
            $table->string('subtotal')->nullable();
            $table->string('subtotal_incl')->nullable();
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
        Schema::dropIfExists('sell_items');
    }
}
