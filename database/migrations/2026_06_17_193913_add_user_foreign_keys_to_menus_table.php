<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // 1. Tambahkan kolom created_by (nullable agar data lama tidak error)
            $table->foreignId('created_by')
                  ->nullable()
                  ->after('sort_order')
                  ->constrained('users')
                  ->onDelete('set null');

            // 2. Tambahkan kolom updated_by (nullable agar data lama tidak error)
            $table->foreignId('updated_by')
                  ->nullable()
                  ->after('created_by')
                  ->constrained('users')
                  ->onDelete('set null');

            // 3. Tambahkan index untuk performa query
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // 1. Hapus foreign key
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            // 2. Hapus kolom
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};