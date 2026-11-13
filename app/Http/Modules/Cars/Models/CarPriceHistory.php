<?php

namespace App\Http\Modules\Cars\Models;

use App\Http\Modules\Sellers\Models\Seller;
use Illuminate\Database\Eloquent\Model;

class CarPriceHistory extends Model
{
    protected $table = 'car_price_histories';

    protected $fillable = [
        'car_id',
        'seller_id',
        'old_price',
        'new_price',
        'price_difference',
        'change_percentage',
        'currency',
        'changed_by_type',
        'changed_by_id',
    ];

    protected $casts = [
        'old_price' => 'float',
        'new_price' => 'float',
        'price_difference' => 'float',
        'change_percentage' => 'float',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
}

