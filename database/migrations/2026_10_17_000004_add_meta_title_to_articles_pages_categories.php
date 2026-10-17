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
        if (Schema::hasTable('articles') && ! Schema::hasColumn('articles', 'meta_title')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->string('meta_title')->nullable()->after('views');
            });
        }

        if (Schema::hasTable('pages') && ! Schema::hasColumn('pages', 'meta_title')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('meta_title')->nullable()->after('order');
            });
        }

        if (Schema::hasTable('categories') && ! Schema::hasColumn('categories', 'meta_title')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('meta_title')->nullable()->after('is_nav');
                $table->text('meta_description')->nullable()->after('meta_title');
                $table->string('meta_keywords')->nullable()->after('meta_description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('articles') && Schema::hasColumn('articles', 'meta_title')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('meta_title');
            });
        }

        if (Schema::hasTable('pages') && Schema::hasColumn('pages', 'meta_title')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('meta_title');
            });
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $columns = array_filter(['meta_title', 'meta_description', 'meta_keywords'], fn ($c) => Schema::hasColumn('categories', $c));
                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
