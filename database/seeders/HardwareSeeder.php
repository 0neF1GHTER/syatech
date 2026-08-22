<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Motherboard;
use App\Models\Product;

class HardwareSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Sample Motherboard
        Motherboard::create([
            'name' => 'MSI Z170M MORTAR',
            'socket' => 'LGA1151_V1',
            'ram_type' => 'DDR4',
            'max_ram_gb' => 64,
            'has_nvme_slot' => true,
        ]);

        Motherboard::create([
            'name' => 'MSI B460M PRO-VDH',
            'socket' => 'LGA1200',
            'ram_type' => 'DDR4',
            'max_ram_gb' => 128,
            'has_nvme_slot' => true,
        ]);

        Motherboard::create([
            'name' => 'ASUS PRIME B450M-K',
            'socket' => 'AM4',
            'ram_type' => 'DDR4',
            'max_ram_gb' => 32,
            'has_nvme_slot' => true,
        ]);

        // 2. Data Sample Produk Upgrade (Kompatibel LGA 1151_V1 / Universal)
        Product::create([
            'title' => 'Intel Core i7-6700 (Processor Upgrade Ideal Z170)',
            'category' => 'cpu',
            'socket_compat' => 'LGA1151_V1',
            'ram_type_compat' => 'Universal',
            'price' => 1250000,
            'seller_name' => 'Toko Komputer Banda',
            'seller_phone' => '081234567890',
            'source_type' => 'local',
        ]);

        Product::create([
            'title' => 'RAM Corsair Vengeance LPX DDR4 16GB (2x8GB) 3200MHz',
            'category' => 'ram',
            'socket_compat' => 'Universal',
            'ram_type_compat' => 'DDR4',
            'price' => 580000,
            'seller_name' => 'Shopee Official Store',
            'seller_phone' => '081234567890',
            'source_type' => 'ecommerce',
        ]);

        Product::create([
            'title' => 'SSD NVMe M.2 512GB RX7 High Speed',
            'category' => 'storage',
            'socket_compat' => 'Universal',
            'ram_type_compat' => 'Universal',
            'price' => 450000,
            'seller_name' => 'Syahrizal PC Store',
            'seller_phone' => '082233445566',
            'source_type' => 'local',
        ]);

        Product::create([
            'title' => 'VGA NVIDIA GTX 1660 Super 6GB GDDR6',
            'category' => 'vga',
            'socket_compat' => 'Universal',
            'ram_type_compat' => 'Universal',
            'price' => 2100000,
            'seller_name' => 'Tokopedia Official',
            'seller_phone' => '081234567890',
            'source_type' => 'ecommerce',
        ]);
    }
}