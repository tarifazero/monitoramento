<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesByRouteCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_by_route_counts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('route_id')
                  ->constrained()
                  ->onDelete('cascade');
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
        Schema::dropIfExists('vehicles_by_route_counts');
    }
}
