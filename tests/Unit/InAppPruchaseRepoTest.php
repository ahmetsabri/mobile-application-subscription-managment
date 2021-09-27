<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Subscription;
use App\Repositories\InAppPurchaseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class InAppPruchaseRepoTest extends TestCase
{
    use RefreshDatabase;
    private InAppPurchaseRepository $in_app_pruchase_reposiyory ;
    private Device $device;
    private array $invalid_recipt;
    private array $valid_recipt;

    public function setUp(): void
    {
        parent::setUp();

        $this->device = Device::factory()->create(['os'=>'ios'])->load('token');
        $this->token = $this->device->token->token;
        $this->in_app_pruchase_reposiyory = new InAppPurchaseRepository($this->token);
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
        $this->in_app_pruchase_reposiyory->updateOrCreateSubscription($this->valid_recipt);

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
        $this->in_app_pruchase_reposiyory->updateOrCreateSubscription($this->valid_recipt);
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

        $this->in_app_pruchase_reposiyory->updateOrCreateSubscription($this->invalid_recipt);

        $this->assertEquals(1, Subscription::count());

        $this->assertDatabaseHas(
            'subscriptions',
            [
                'token' => $this->token,
                'status' => 'canceled'
            ]
        );
    }
}
