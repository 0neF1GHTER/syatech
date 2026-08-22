<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motherboards', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Contoh: MSI Z170M MORTAR
            $table->string('socket');         // Contoh: LGA1151_V1, LGA1200, AM4
            $table->string('ram_type');       // Contoh: DDR4
            $table->integer('max_ram_gb');    // Contoh: 64
            $table->boolean('has_nvme_slot'); // true / false
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motherboards');
    }
};