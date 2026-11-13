<?php

namespace App\Http\Modules\Notifications\Models;

use App\Http\Modules\Sellers\Models\Seller;
use App\Http\Modules\Users\Models\User;
use Gomaa\Base\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;

class Notification extends BaseModel
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'user_id',
        'seller_id',
        'type',
        'title',
        'body',
        'data',
        'is_read',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'title' => 'array',
        'body' => 'array',
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('seller_id'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('title'),
            AllowedFilter::exact('body'),
            AllowedFilter::exact('is_read'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
        ];
    }

    public static function getDefaultSort()
    {
        return '-created_at';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
}
