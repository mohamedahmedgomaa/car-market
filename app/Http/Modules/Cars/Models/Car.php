<?php

namespace App\Http\Modules\Cars\Models;
use App\Http\Modules\CarFeatures\Models\CarFeature;
use App\Http\Modules\Countries\Models\Country;
use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use App\Http\Modules\CarImages\Models\CarImage;
use App\Http\Modules\CarFeaturePivots\Models\CarFeaturePivot;
use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Models\Models\Model;
use App\Http\Modules\Brands\Models\Brand;
use App\Http\Modules\Sellers\Models\Seller;
use App\Http\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;
use Spatie\Translatable\HasTranslations;

class Car extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'cars';

    protected $fillable = ['id', 'seller_id', 'brand_id', 'model_id', 'city_id', 'country_id', 'title', 'description', 'price', 'year', 'mileage', 'transmission', 'fuel_type', 'drivetrain', 'color', 'condition', 'status', 'created_at', 'updated_at'];

    public $translatable = ['title', 'description'];

    public static function getAllowedSorts(): array
    {
        return [
            'price',
            'created_at',
            'favorites_count',
        ];
    }

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('global'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('seller_id'),
            AllowedFilter::exact('brand_id'),
            AllowedFilter::exact('model_id'),
            AllowedFilter::exact('city_id'),
            AllowedFilter::exact('country_id'),
            AllowedFilter::exact('title'),
            AllowedFilter::exact('description'),
            AllowedFilter::exact('price'),
            AllowedFilter::exact('year'),
            AllowedFilter::exact('mileage'),
            AllowedFilter::exact('transmission'),
            AllowedFilter::exact('fuel_type'),
            AllowedFilter::exact('drivetrain'),
            AllowedFilter::exact('color'),
            AllowedFilter::exact('condition'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::scope('year_between'),
            AllowedFilter::scope('price_between'),
            AllowedFilter::scope('mileage_between'),
            AllowedFilter::scope('top_expensive'),
            AllowedFilter::scope('user_id'),
        ];
    }

    public static function getDefaultIncludedRelationsCount(): array
    {
        return ['favorites'];
    }

    public static function getDefaultSort()
    {
        return '-created_at';
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function carFeaturePivots()
    {
        return $this->hasMany(CarFeaturePivot::class, 'car_id');
    }

    public function features()
    {
        return $this->belongsToMany(CarFeature::class,'car_feature_pivots');
    }

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorite_cars', 'car_id', 'user_id');
    }

    public function favoriteCars()
    {
        return $this->hasMany(FavoriteCar::class, 'car_id');
    }

    public function scopeGlobal(Builder $query, $date): Builder
    {
        return $query->where("title", 'like', "%$date%");
    }

    public function scopeYearBetween(Builder $query, $value): Builder
    {
        $from = data_get($value, 'from');
        $to   = data_get($value, 'to');

        return $query
            ->when($from !== null && $from !== '', fn ($q) => $q->where('year', '>=', (int) $from))
            ->when($to   !== null && $to   !== '', fn ($q) => $q->where('year', '<=', (int) $to));
    }

    public function scopePriceBetween(Builder $query, $value): Builder
    {
        $from = data_get($value, 'from');
        $to   = data_get($value, 'to');

        return $query
            ->when($from !== null && $from !== '', fn ($q) => $q->where('price', '>=', (float) $from))
            ->when($to   !== null && $to   !== '', fn ($q) => $q->where('price', '<=', (float) $to));
    }

    public function scopeMileageBetween(Builder $query, $value): Builder
    {
        $from = data_get($value, 'from');
        $to   = data_get($value, 'to');

        return $query
            ->when($from !== null && $from !== '', fn ($q) => $q->where('mileage', '>=', (int) $from))
            ->when($to   !== null && $to   !== '', fn ($q) => $q->where('mileage', '<=', (int) $to));
    }

    public function scopeTopExpensive(Builder $query, $value): Builder
    {
        // لو الفلتر اتبعت بأي قيمة truthy
        if (!filter_var($value, FILTER_VALIDATE_BOOLEAN) && $value != 1 && $value !== '1') {
            return $query;
        }

        return $query->orderByDesc('price');
    }

    public function scopeUserId($query, $userId)
    {
        if (empty($userId)) return $query;

        return $query->whereHas('favorites', function ($q) use ($userId) {
            $q->where('users.id', (int) $userId);
        });
    }
}
