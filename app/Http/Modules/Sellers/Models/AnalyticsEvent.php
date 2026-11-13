<?php

namespace App\Http\Modules\Sellers\Models;

use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $table = 'analytics_events';

    public $timestamps = false;

    public const EVENT_CAR_VIEW = 'car_view';
    public const EVENT_PROFILE_VIEW = 'profile_view';
    public const EVENT_WHATSAPP_CLICK = 'whatsapp_click';
    public const EVENT_PHONE_CLICK = 'phone_click';
    public const EVENT_SHARE_CLICK = 'share_click';
    public const EVENT_MAP_CLICK = 'map_click';
    public const EVENT_SOCIAL_CLICK = 'social_click';

    public const ALLOWED_EVENTS = [
        self::EVENT_CAR_VIEW,
        self::EVENT_PROFILE_VIEW,
        self::EVENT_WHATSAPP_CLICK,
        self::EVENT_PHONE_CLICK,
        self::EVENT_SHARE_CLICK,
        self::EVENT_MAP_CLICK,
        self::EVENT_SOCIAL_CLICK,
    ];

    protected $fillable = [
        'seller_id',
        'car_id',
        'user_id',
        'event_type',
        'ip_hash',
        'device_type',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

