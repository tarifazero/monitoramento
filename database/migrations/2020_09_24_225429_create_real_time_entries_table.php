<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealTimeEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void */ public function up()
    {
        Schema::create('real_time_entries', function (Blueprint $table) {
            $table->uuid('id')
                  ->primary();
            $table->integer('vehicle_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->integer('route_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->dateTime('timestamp')
                  ->index();
            $table->double('latitude');
            $table->double('longitude');
            $table->integer('speed');
            $table->integer('travel_direction')
                  ->nullable();
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
