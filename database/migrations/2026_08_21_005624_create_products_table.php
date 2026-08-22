<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');            // Nama / Judul Barang
            $table->string('category');         // ram, cpu, vga, storage, motherboard, mouse, keyboard, monitor, aksesoris
            $table->string('socket_compat')->nullable();   // LGA1151_V1, LGA1200, AM4, Universal
            $table->string('ram_type_compat')->nullable(); // DDR3 / DDR4 / DDR5 / Universal
            $table->integer('price');           // Harga dalam Rupiah
            $table->string('seller_name');      // Nama Penjual / Toko
            $table->string('seller_phone');     // No WA Penjual
            $table->enum('source_type', ['local', 'ecommerce'])->default('local'); // Marketplace Lokal atau Shopee/Tokopedia
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold'])->default('pending'); // Menambahkan status 'sold'
            $table->string('approved_by')->nullable();  // Nama Admin/Owner yang menyetujui
            $table->text('description')->nullable();    // Deskripsi / Informasi Tambahan
            $table->string('image_path')->nullable();   // File Foto Produk
            $table->text('external_link')->nullable();  // Link Shopee / Tokopedia
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};