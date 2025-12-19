<?php

namespace App\Http\Modules\Brands\Models;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Models\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;
use Spatie\Translatable\HasTranslations;

class Brand extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'brands';

    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];
    public $translatable = ['name'];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('global'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at')
        ];
    }

    public static function getDefaultSort()
    {
        return '-created_at';
    }



    public function models()
    {
        return $this->hasMany(Model::class, 'brand_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'brand_id');
    }
    public function scopeGlobal(Builder $query, $date): Builder
    {
        return $query->where("name", 'like', "%$date%");
    }
}
