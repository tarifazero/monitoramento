<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gtfs_fetch_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('service_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->date('date')
                  ->index();
            $table->enum('exception_type', [1, 2]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_dates');
    }
}
