<?php

namespace App\Listeners;

use App\Events\Device\DeviceRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class GenerateToken implements ShouldQueue
{
    public function handle(DeviceRegistered $event)
    {
        $device = $event->getDevice();
        Cache::rememberForever("{$device->hashToken()}", function () use ($device) {
            return $device->generateToken()->load('device');
        });
    }
}
