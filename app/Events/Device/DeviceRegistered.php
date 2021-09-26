<?php

namespace App\Events\Device;

use App\Models\Device;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceRegistered
{
    use Dispatchable;
    use SerializesModels;

    public Device $device ;

    public function __construct($device)
    {
        $this->device = $device;
    }

    public function getDevice(): Device
    {
        return $this->device;
    }
}
