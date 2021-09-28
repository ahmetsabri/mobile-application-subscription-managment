<?php

namespace Tests\Feature;

use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterNewDevice()
    {
        $this->postJson('/api/register', [
            'uid' => Str::random(10),
            'app_id' => Str::random(5),
            'language' => 'ees',
            'os' => 'ios'
        ])->assertOk();
    }
}
