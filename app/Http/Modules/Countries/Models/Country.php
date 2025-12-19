<?php

namespace App\Http\Modules\Countries\Models;
use App\Http\Modules\Cities\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;
use Spatie\Translatable\HasTranslations;

class Country extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'countries';

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

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id');
    }


    public function scopeGlobal(Builder $query, $date): Builder
    {
        return $query->where("name", 'like', "%$date%");
    }
}
