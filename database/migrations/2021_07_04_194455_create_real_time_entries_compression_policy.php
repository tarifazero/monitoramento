<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRealTimeEntriesCompressionPolicy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE real_time_entries
            SET (
                timescaledb.compress,
                timescaledb.compress_orderby = 'timestamp',
                timescaledb.compress_segmentby = 'vehicle_id, route_id'
            );
        ");
        DB::statement("SELECT add_compression_policy('real_time_entries', INTERVAL '3 months', true);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("SELECT remove_compression_policy('real_time_entries', true);");
        DB::statement("SELECT decompress_chunk(i, true) from show_chunks('real_time_entries') i;");
    }
}
