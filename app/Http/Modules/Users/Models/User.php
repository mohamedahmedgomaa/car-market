<?php

namespace App\Http\Modules\Users\Models;

use App\Http\Modules\Notifications\Models\Notification;
use Gomaa\Base\Base\Models\BaseAuthModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Spatie\QueryBuilder\AllowedFilter;

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
}
