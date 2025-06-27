<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Load all SQL files from the procedures directory
        $proceduresPath = database_path('procedures');
        $files = File::files($proceduresPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $sql = File::get($file->getPathname());
                DB::unprepared($sql);
            }
        }

        // Load all SQL files from the triggers directory
        $triggersPath = database_path('triggers');
        $files = File::files($triggersPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $sql = File::get($file->getPathname());
                DB::unprepared($sql);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop all procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS GetSalesByCategory');
        DB::unprepared('DROP PROCEDURE IF EXISTS GetLowStockProducts');

        // Drop all triggers
        DB::unprepared('DROP TRIGGER IF EXISTS after_order_detail_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_product_price_update');
        
        // Drop the price_logs table
        DB::unprepared('DROP TABLE IF EXISTS price_logs');
    }
};