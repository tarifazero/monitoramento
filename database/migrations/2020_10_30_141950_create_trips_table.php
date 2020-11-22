<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gtfs_fetch_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('route_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('service_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('gtfs_id')
                  ->unique();
            $table->string('headsign');
            $table->enum('direction_id', [0, 1]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
