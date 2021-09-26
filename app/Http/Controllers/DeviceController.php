<?php

namespace App\Http\Controllers;

use App\Events\Device\DeviceRegistered;
use App\Http\Requests\Device\RegisterDeviceRequest;
use App\Models\Device;
use Illuminate\Support\Facades\Cache;

class DeviceController extends Controller
{
    public function register(RegisterDeviceRequest $request)
    {
        $device = Device::firstOrCreate($request->validated());

        event(new DeviceRegistered($device));

        $client_token = $device->getToken();

        return response()->json(compact('client_token'));
    }
}
