<?php

namespace App\Http\Modules\Cities\Models;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Countries\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;
use Spatie\Translatable\HasTranslations;

class City extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'cities';

    protected $fillable = ['id', 'country_id', 'name', 'created_at', 'updated_at'];
    public $translatable = ['name'];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('global'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('country_id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at')
        ];
    }

    public static function getDefaultSort()
    {
        return '-created_at';
    }


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'city_id');
    }

    public function scopeGlobal(Builder $query, $date): Builder
    {
        return $query->where("name", 'like', "%$date%");
    }
}
