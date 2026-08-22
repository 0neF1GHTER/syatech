<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    // Membuka kuncian keamanan agar semua kolom (user_name, action, description) bisa diisi otomatis
    protected $guarded = [];
}