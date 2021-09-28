<?php

namespace Tests\Feature;

use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function testViewSubscriptionStatus()
    {
        $device_token = Device::factory()->create()->load('token');
        $this->getJson("/api/check-subscription?client_token={$device_token->token->token}")->assertOk();
    }
}
