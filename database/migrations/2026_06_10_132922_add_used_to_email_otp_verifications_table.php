<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu apakah kolom 'used' sudah ada di tabel
        if (!Schema::hasColumn('email_otp_verifications', 'used')) {
            Schema::table('email_otp_verifications', function (Blueprint $table) {
                $table->integer('used')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('email_otp_verifications', 'used')) {
            Schema::table('email_otp_verifications', function (Blueprint $table) {
                $table->dropColumn('used');
            });
        }
    }
};