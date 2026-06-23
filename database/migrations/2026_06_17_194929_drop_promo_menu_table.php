<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('promo_menu');
    }

    public function down(): void
    {
        // Tidak perlu membuat ulang
        Schema::dropIfExists('promo_menu');
    }
};