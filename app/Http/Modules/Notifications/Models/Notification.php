<?php

namespace App\Http\Modules\Notifications\Models;
use App\Http\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Gomaa\Base\Base\Models\BaseModel;

class Notification extends BaseModel
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = ['id', 'user_id', 'title', 'body', 'is_read', 'created_at', 'updated_at'];

    public static function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('title'),
            AllowedFilter::exact('body'),
            AllowedFilter::exact('is_read'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at')
        ];
    }

    public static function getDefaultSort()
    {
        return '-created_at';
    }

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
