<?php
// database/migrations/2024_01_01_000010_create_event_reservations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            
            // Foreign key ke events
            $table->foreignId('event_id')
                  ->constrained('events')
                  ->onDelete('cascade');
            
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->integer('number_of_tickets');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            
            // Foreign key ke users (customer) - opsional jika user login
            $table->foreignId('user_id')->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
                  
            $table->timestamps();
            
            // Indexes
            $table->index('booking_code');
            $table->index('event_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_reservations');
    }
};