<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SubscriptionRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private SubscriptionRepository $subscription_repository ;
    private Device $device;
    private array $invalid_recipt;
    private array $valid_recipt;

    public function setUp(): void
    {
        parent::setUp();

        $this->device = Device::factory()->create(['os'=>'ios'])->load('token');
        $this->token = $this->device->token->token;
        Cache::remember($this->token, 3600, function () {
            return $this->device->token;
        });

        $this->subscription_repository = new SubscriptionRepository($this->token);
        $this->valid_recipt = [
            'status' => true,
            'expire_at' => now()->addMonth(),
        ];

        $this->invalid_recipt = [
            'status' => false,
            'expire_at' => now()->subMonth(),
        ];
    }
    public function testPruchaseFirstTime()
    {
        $this->assertEquals(0, Subscription::count());
        $this->subscription_repository->updateOrCreateSubscription($this->valid_recipt);

        $this->assertDatabaseHas(
            'subscriptions',
            [
                'token' => $this->token,
                'status' => 'started'
            ]
        );
    }

    public function testRenewSubscription()
    {
        Subscription::factory()->create(
            [
            'status'=>'canceled',
            'token'=>$this->token
            ]
        );
        $this->subscription_repository->updateOrCreateSubscription($this->valid_recipt);
        $this->assertEquals(1, Subscription::count());

        $this->assertDatabaseHas(
            'subscriptions',
            [
                'token' => $this->token,
                'status' => 'renewed'
            ]
        );
        $this->assertEquals(1, Subscription::count());
    }

    public function testCancelSubscriptionIdReciptStatusIsFalse()
    {
        $this->assertEquals(0, Subscription::count());

        Subscription::factory()->create(['status'=>'started', 'token'=>$this->token]);

        $this->assertEquals(1, Subscription::count());

        $this->subscription_repository->updateOrCreateSubscription($this->invalid_recipt);

        $this->assertEquals(1, Subscription::count());

        $this->assertDatabaseHas(
            'subscriptions',
            [
                'token' => $this->token,
                'status' => 'canceled'
            ]
        );
        $response = $this->getJson("/api/check-subscription?client_token={$this->token}")->assertOk();
        $response->assertJsonPath('status', 'canceled');
    }
}
