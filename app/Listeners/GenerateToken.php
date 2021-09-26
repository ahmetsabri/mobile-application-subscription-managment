<?php

namespace App\Listeners;

use App\Events\Device\DeviceRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class GenerateToken implements ShouldQueue
{
    public function handle(DeviceRegistered $event)
    {
        $device = $event->getDevice();
        $one_day = 60 * 60 * 24;
        Cache::remember("{$device->hashToken()}", $one_day, function () use ($device) {
            return $device->generateToken()->token;
        });
    }
}
