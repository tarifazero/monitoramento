<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealTimeEntriesHypertable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('real_time_entries', function (Blueprint $table) {
            $table->dropIndex('real_time_entries_timestamp_index');
            $table->dropPrimary('real_time_entries_pkey');
            $table->dropColumn('id');
        });

        DB::statement("SELECT create_hypertable('real_time_entries', 'timestamp', migrate_data=>TRUE);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
