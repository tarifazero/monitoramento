<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRealTimeEntriesRetentionPolicy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SELECT add_retention_policy('real_time_entries', INTERVAL '1 year', true);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("SELECT remove_retention_policy('real_time_entries', true);");
    }
}
