<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_vehicle', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('route_id')
                ->constrained();
            $table->foreignId('vehicle_id')
                ->constrained();
            $table->dateTime('created_at')
                  ->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_vehicle');
    }
}
