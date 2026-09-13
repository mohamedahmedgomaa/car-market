<?php

namespace App\Http\Modules\Banners\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'image_path',
        'is_active',
        'type',
        'link',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
