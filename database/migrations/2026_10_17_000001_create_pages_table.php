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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->longText('body');
            $table->text('excerpt')->nullable();
            $table->string('image_path')->nullable();
            $table->string('template')->default('default'); // 'default', 'contact', 'team', etc.
            $table->boolean('is_published')->default(true)->index();
            $table->dateTime('published_at')->nullable();
            $table->integer('order')->default(0)->index();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
