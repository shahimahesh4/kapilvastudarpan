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
        Schema::create('rashifals', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->index();
            $table->string('name');
            $table->string('full_name')->nullable();
            $table->string('english_name')->nullable();
            $table->string('letters')->nullable();
            $table->unsignedTinyInteger('order')->default(0);
            $table->string('element')->nullable();
            $table->string('ruler')->nullable();
            $table->string('lucky_number')->nullable();
            $table->string('lucky_color')->nullable();
            $table->string('lucky_direction')->nullable();
            $table->string('lucky_day')->nullable();
            $table->unsignedTinyInteger('icon_index')->default(0);

            // Predictions
            $table->longText('dainik')->nullable();
            $table->longText('saptahik')->nullable();
            $table->longText('masik')->nullable();
            $table->longText('varshik')->nullable();

            $table->date('prediction_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rashifals');
    }
};
