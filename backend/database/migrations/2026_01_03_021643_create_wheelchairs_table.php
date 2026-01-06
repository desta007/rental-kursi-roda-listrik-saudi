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
        Schema::create('wheelchairs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('wheelchair_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('station_id')->constrained()->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->integer('battery_capacity')->default(100);
            $table->enum('status', ['available', 'rented', 'maintenance', 'retired'])->default('available');
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->json('photos')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wheelchairs');
    }
};
