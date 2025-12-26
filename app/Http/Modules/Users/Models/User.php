<?php

namespace App\Http\Modules\Users\Models;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Notifications\Models\Notification;
use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use Gomaa\Base\Base\Models\BaseAuthModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;

class User extends BaseAuthModel
{
    use HasFactory, HasApiTokens;

    protected $table = 'users';

    protected $fillable = ['id', 'name', 'email', 'password', 'phone', 'is_active', 'created_at', 'updated_at'];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('email'),
            AllowedFilter::exact('password'),
            AllowedFilter::exact('phone'),
            AllowedFilter::exact('is_active'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at')
        ];
    }

    public static function getDefaultSort()
    {
        return '-created_at';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function favoriteCars()
    {
        return $this->belongsToMany(Car::class, 'favorites', 'user_id', 'car_id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
