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
        Schema::create('wheelchair_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('image')->nullable();
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('weekly_rate', 10, 2);
            $table->decimal('monthly_rate', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->integer('battery_range_km')->default(25);
            $table->integer('max_weight_kg')->default(120);
            $table->integer('max_speed_kmh')->default(6);
            $table->json('features')->nullable(); // array of features
            $table->json('specifications')->nullable(); // detailed specs
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wheelchair_types');
    }
};
