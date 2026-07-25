<?php

namespace App\Http\Modules\FavoriteCars\Models;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;

class FavoriteCar extends BaseModel
{
    use HasFactory;

    protected $table = 'favorite_cars';

    protected $fillable = ['id', 'user_id', 'car_id', 'created_at'];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('car_id'),
            AllowedFilter::exact('created_at')
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

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
