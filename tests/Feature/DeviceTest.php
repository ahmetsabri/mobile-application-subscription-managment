<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'language' => 'Turkish',
            'os' => 'ios'
        ])->dump()->assertOk();
    }
}
