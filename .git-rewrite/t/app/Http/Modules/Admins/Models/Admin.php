<?php

namespace App\Http\Modules\Admins\Models;

use App\Http\Modules\Admins\Filters\GlobalSearch;
use Gomaa\Base\Base\Models\BaseAuthModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;

class Admin extends BaseAuthModel
{
    use HasFactory, HasApiTokens;

    protected $table = 'admins';

    protected $fillable = ['id', 'name', 'email', 'password', 'phone', 'is_active', 'created_at', 'updated_at'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::custom('global', new GlobalSearch()),
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


}
