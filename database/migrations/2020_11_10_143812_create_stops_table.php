<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gtfs_fetch_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('gtfs_id')
                  ->unique();
            $table->string('name');
            $table->double('latitude');
            $table->double('longitude');
            $table->enum('location_type', [0, 1, 2, 3, 4])
                ->default(0);
            $table->foreignId('parent_station')
                  ->nullable()
                  ->constrained('stops')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stops');
    }
}
