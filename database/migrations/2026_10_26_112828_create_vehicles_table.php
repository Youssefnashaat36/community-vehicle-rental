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
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('provider_id')->constrained('users');
        $table->enum('type', ['bicycle', 'scooter', 'motorbike', 'car']);
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('image')->nullable();
        $table->decimal('price_per_hour', 8, 2);
        $table->string('pickup_location');
        $table->enum('status', ['pending_approval', 'active', 'rejected', 'inactive'])
              ->default('pending_approval');
        $table->text('rejection_reason')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
