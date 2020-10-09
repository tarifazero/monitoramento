<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealTimeEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('real_time_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('route_real_time_id');
            $table->integer('vehicle_real_time_id');
            $table->integer('event');
            $table->dateTime('timestamp');
            $table->double('latitude');
            $table->double('longitude');
            $table->integer('speed');
            $table->integer('cardinal_direction')->nullable();
            $table->integer('travel_direction')->nullable();
            $table->integer('distance')->nullable();
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('real_time_entries');
    }
}
