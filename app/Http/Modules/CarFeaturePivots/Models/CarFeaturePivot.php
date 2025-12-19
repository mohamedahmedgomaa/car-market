<?php

namespace App\Http\Modules\CarFeaturePivots\Models;
use App\Http\Modules\CarFeatures\Models\CarFeature;
use App\Http\Modules\Cars\Models\Car;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;

class CarFeaturePivot extends BaseModel
{
    use HasFactory;

    protected $table = 'car_feature_pivots';

    protected $fillable = ['id', 'car_id', 'car_feature_id', 'created_at', 'updated_at'];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('car_id'),
            AllowedFilter::exact('car_feature_id'),
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

    public function carFeature()
    {
        return $this->belongsTo(CarFeature::class, 'car_feature_id');
    }
}
