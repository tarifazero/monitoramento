<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiveVehicleCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_vehicle_counts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('resolution', [
                'hour',
                'day',
                'month',
            ]);
            $table->dateTime('time')
                  ->index();
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
        Schema::dropIfExists('active_vehicle_counts');
    }
}
