<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Repositories\SubscriptionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class InAppPruchaseTest extends TestCase
{
    use RefreshDatabase;

    private $subscription_repository ;
    private Device $device;
    public function setUp(): void
    {
        parent::setUp();

        $this->device = Device::factory()->create(['os'=>'ios'])->load('token');
        $this->token = $this->device->token->token;

        Cache::remember($this->token, 3600, function () {
            return $this->device->token;
        });

        $this->subscription_repository = new SubscriptionRepository($this->token);
    }

    public function testPruchase()
    {
        $this->getJson("/api/pruchase?client_token={$this->token}&recipt=321", [
            'username' => 'test',
            'password' => 'test'
        ])->assertOk();
    }
}
