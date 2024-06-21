<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetreadTyresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retread_tyres', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('retread_id')->unsigned()->nullable();
            $table->foreign('retread_id')->references('id')->on('retreads')->onDelete('cascade');
            $table->bigInteger('tyre_dispatch_id')->unsigned()->nullable();
            $table->bigInteger('horse_id')->unsigned()->nullable();
            $table->foreign('horse_id')->references('id')->on('horses')->onDelete('cascade');
            $table->bigInteger('vehicle_id')->unsigned()->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->bigInteger('trailer_id')->unsigned()->nullable();
            $table->foreign('trailer_id')->references('id')->on('trailers')->onDelete('cascade');
            $table->string('tyre_number')->nullable();
            $table->string('name')->nullable();
            $table->string('width')->nullable();
            $table->string('aspect_ratio')->nullable();
            $table->string('diameter')->nullable();
            $table->string('rate')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('retread_tyres');
    }
}
