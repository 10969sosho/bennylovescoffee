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
        Schema::create('featured_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable(); // Judul khusus untuk featured (opsional)
            $table->text('description')->nullable(); // Deskripsi khusus untuk featured (opsional)
            $table->integer('sort_order')->default(0); // Urutan tampil (0 = paling atas)
            $table->boolean('is_active')->default(true); // Status aktif/tidak
            $table->timestamp('featured_at')->nullable(); // Kapan mulai di-featured
            $table->timestamp('unfeatured_at')->nullable(); // Kapan berhenti di-featured (opsional)
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['is_active', 'sort_order']);
            $table->index('featured_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_products');
    }
};
