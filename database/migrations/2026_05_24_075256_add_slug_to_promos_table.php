<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// database/migrations/xxxx_add_slug_to_promos_table.php
public function up()
{
    Schema::table('promos', function (Blueprint $table) {
        if (!Schema::hasColumn('promos', 'slug')) {
            $table->string('slug')->nullable()->after('title');
            $table->index('slug');
        }
    });
}

public function down()
{
    Schema::table('promos', function (Blueprint $table) {
        $table->dropColumn('slug');
    });
}
};
