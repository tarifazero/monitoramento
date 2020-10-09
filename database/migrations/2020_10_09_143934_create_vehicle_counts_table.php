<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_counts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('time')
                  ->index();
            $table->foreignId('route_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->integer('count')
                  ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_counts');
    }
}
