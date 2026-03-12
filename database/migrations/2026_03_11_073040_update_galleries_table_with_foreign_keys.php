<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Hapus tabel galleries jika ada (backup data dulu!)
        Schema::dropIfExists('galleries');
        
        // Buat ulang tabel galleries dengan foreign keys yang jelas
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['image', 'video'])->default('image');
            $table->string('file_path');
            
            // Foreign keys ke masing-masing tabel (nullable karena hanya satu yang terisi)
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->foreignId('promo_id')->nullable()->constrained('promos')->onDelete('cascade');
            $table->foreignId('testimonial_id')->nullable()->constrained('testimonials')->onDelete('cascade');
            
            // Kolom tambahan
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Index untuk performa
            $table->index('menu_id');
            $table->index('event_id');
            $table->index('promo_id');
            $table->index('testimonial_id');
            $table->index('is_featured');
            $table->index('category');
            
            // Unique constraint agar satu gambar tidak dipakai di multiple entitas
            $table->unique(['menu_id', 'sort_order'])->whereNotNull('menu_id');
            $table->unique(['event_id', 'sort_order'])->whereNotNull('event_id');
            $table->unique(['promo_id', 'sort_order'])->whereNotNull('promo_id');
            $table->unique(['testimonial_id', 'sort_order'])->whereNotNull('testimonial_id');
        });
        
        // Tambahkan trigger/constraint untuk memastikan hanya satu foreign key yang terisi
        DB::statement('
            ALTER TABLE galleries 
            ADD CONSTRAINT check_single_parent 
            CHECK (
                (menu_id IS NOT NULL AND event_id IS NULL AND promo_id IS NULL AND testimonial_id IS NULL) OR
                (menu_id IS NULL AND event_id IS NOT NULL AND promo_id IS NULL AND testimonial_id IS NULL) OR
                (menu_id IS NULL AND event_id IS NULL AND promo_id IS NOT NULL AND testimonial_id IS NULL) OR
                (menu_id IS NULL AND event_id IS NULL AND promo_id IS NULL AND testimonial_id IS NOT NULL)
            )
        ');
    }

    public function down()
    {
        Schema::dropIfExists('galleries');
        
        // Kembalikan ke struktur lama jika perlu
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['image', 'video'])->default('image');
            $table->string('file_path');
            $table->unsignedBigInteger('galleryable_id');
            $table->string('galleryable_type');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['galleryable_id', 'galleryable_type']);
        });
    }
};