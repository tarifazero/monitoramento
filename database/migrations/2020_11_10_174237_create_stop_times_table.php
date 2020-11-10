<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stop_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gtfs_fetch_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('trip_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('stop_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->time('arrival_time')
                ->nullable();
            $table->time('departure_time')
                ->nullable();
            $table->integer('stop_sequence');
            $table->enum('timepoint', [0, 1])
                ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stop_times');
    }
}
