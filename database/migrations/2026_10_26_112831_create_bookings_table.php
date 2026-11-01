<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
        $table->foreignId('customer_id')->constrained('users');
        $table->foreignId('vehicle_id')->constrained('vehicles');
        $table->dateTime('start_at');
        $table->dateTime('end_at');
        $table->decimal('total_price', 10, 2);
        $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled', 'expired'])
        ->default('pending');
        $table->boolean('is_overdue')->default(false);
        $table->timestamp('reminder_sent_at')->nullable();
        $table->timestamps();

    $table->index(['vehicle_id', 'start_at', 'end_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
