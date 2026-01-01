<?php

namespace App\Http\Modules\Sellers\Models;
use App\Http\Modules\Cars\Models\Car;
use Gomaa\Base\Base\Models\BaseAuthModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;

class Seller extends BaseAuthModel
{
    use HasFactory, HasApiTokens;
    use HasTranslations;

    protected $table = 'sellers';

    protected $fillable = ['id', 'name', 'email', 'password', 'phone', 'store_name', 'store_description', 'store_logo', 'business_license', 'bank_account', 'is_verified', 'is_active', 'created_at', 'updated_at'];
    public $translatable = ['store_name', 'store_description'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function getAllowedSorts(): array
    {
        return [
            'created_at',
            'cars_count',
        ];
    }

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('global'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('email'),
            AllowedFilter::exact('password'),
            AllowedFilter::exact('phone'),
            AllowedFilter::exact('store_name'),
            AllowedFilter::exact('store_description'),
            AllowedFilter::exact('store_logo'),
            AllowedFilter::exact('business_license'),
            AllowedFilter::exact('bank_account'),
            AllowedFilter::exact('is_verified'),
            AllowedFilter::exact('is_active'),
            AllowedFilter::exact('cars.status'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at')
        ];
    }

    public static function getDefaultSort()
    {
        return '-created_at';
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'seller_id');
    }
    public static function getDefaultIncludedRelationsCount(): array
    {
        return ['cars'];
    }

    public function scopeGlobal(Builder $query, $date): Builder
    {
        return $query->where("name", 'like', "%$date%")
                     ->orWhere("email", 'like', "%$date%")
                     ->orWhere("phone", 'like', "%$date%")
                     ->orWhere("store_name", 'like', "%$date%")
                     ->orWhere("store_description", 'like', "%$date%");
    }
}
