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
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
            if (!Schema::hasColumn('articles', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('content');
            }
            if (!Schema::hasColumn('articles', 'featured_image')) {
                $table->string('featured_image')->nullable()->after('excerpt');
            }
            if (!Schema::hasColumn('articles', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('featured_image');
            }
            if (!Schema::hasColumn('articles', 'published_at')) {
                $table->datetime('published_at')->nullable()->after('is_published');
            }
            if (!Schema::hasColumn('articles', 'views')) {
                $table->integer('views')->default(0)->after('published_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['slug', 'excerpt', 'featured_image', 'is_published', 'published_at', 'views']);
        });
    }
};
