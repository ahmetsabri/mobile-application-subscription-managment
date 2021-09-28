<?php

namespace App\Models;

use App\Jobs\UpdateApplicationStatistics;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'status', 'subscribed_at', 'valid_until', 'canceled_at'];

    public static function boot()
    {
        parent::boot();
        static::saved(function (Model $subscription) {
            if ($subscription->isDirty('status')) {
                $app_id = Cache::get($subscription->token)->device->app_id;
                UpdateApplicationStatistics::dispatch($app_id, $subscription->status);
            }
        });
    }

    public function setSubscriptionCahce(string $token)
    {
        Cache::forget("sub-{$token}");

        Cache::rememberForever("sub-{$token}", function () {
            return $this->status;
        });
    }
}
