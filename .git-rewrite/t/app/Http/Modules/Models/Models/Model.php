<?php

namespace App\Http\Modules\Models\Models;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Brands\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;
use Spatie\Translatable\HasTranslations;

class Model extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'models';

    protected $fillable = ['id', 'brand_id', 'name', 'created_at', 'updated_at'];
    public $translatable = ['name'];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('global'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('brand_id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at')
        ];
    }

    public static function getDefaultSort()
    {
        return '-created_at';
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'model_id');
    }

    public function scopeGlobal(Builder $query, $date): Builder
    {
        return $query->where("name", 'like', "%$date%");
    }
}
