<?php

namespace App\Http\Modules\CarImages\Models;
use App\Http\Modules\Cars\Models\Car;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;

class CarImage extends BaseModel
{
    use HasFactory;

    protected $table = 'car_images';

    protected $fillable = ['id', 'car_id', 'path', 'is_main', 'created_at', 'updated_at'];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('car_id'),
            AllowedFilter::exact('path'),
            AllowedFilter::exact('is_main'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at')
        ];
    }

    public static function getDefaultSort()
    {
        return '-created_at';
    }

    
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
