<?php
// database/migrations/2026_06_02_090000_update_pool_tickets_status_constraint.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Hapus constraint lama
        DB::statement("ALTER TABLE pool_tickets DROP CONSTRAINT IF EXISTS pool_tickets_status_check");
        
        // Buat constraint baru dengan nilai 'pending'
        DB::statement("ALTER TABLE pool_tickets ADD CONSTRAINT pool_tickets_status_check CHECK (status IN ('active', 'used', 'expired', 'cancelled', 'pending'))");
        
        // Update data lama: tiket yang status 'active' tapi belum lunas menjadi 'pending'
        DB::table('pool_tickets')
            ->where('status', 'active')
            ->where('payment_status', '!=', 'paid')
            ->update(['status' => 'pending']);
    }

    public function down()
    {
        DB::statement("ALTER TABLE pool_tickets DROP CONSTRAINT IF EXISTS pool_tickets_status_check");
        DB::statement("ALTER TABLE pool_tickets ADD CONSTRAINT pool_tickets_status_check CHECK (status IN ('active', 'used', 'expired', 'cancelled'))");
    }
};