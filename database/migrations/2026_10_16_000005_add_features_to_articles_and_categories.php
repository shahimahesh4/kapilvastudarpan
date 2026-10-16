<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('views')->default(0)->index()->after('is_featured');
            $table->boolean('is_breaking')->default(false)->index()->after('is_featured');
            $table->text('meta_description')->nullable()->after('source_url');
            $table->string('meta_keywords')->nullable()->after('meta_description');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('description');
            $table->boolean('is_nav')->default(true)->after('order');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex(['views']);
            $table->dropIndex(['is_breaking']);
            $table->dropColumn(['views', 'is_breaking', 'meta_description', 'meta_keywords']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['order', 'is_nav']);
        });
    }
};
