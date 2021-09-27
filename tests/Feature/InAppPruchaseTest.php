<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Subscription;
use App\Repositories\InAppPurchaseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InAppPruchaseTest extends TestCase
{
    use RefreshDatabase;

    private $in_app_pruchase_reposiyory ;
    private Device $device;
    public function setUp(): void
    {
        parent::setUp();

        $this->device = Device::factory()->create(['os'=>'ios'])->load('token');
        $this->token = $this->device->token->token;
        $this->in_app_pruchase_reposiyory = new InAppPurchaseRepository($this->token);
    }

    public function testPruchase()
    {
        $this->getJson("/api/pruchase?client_token={$this->token}&recipt=321", [
            'username' => 'test',
            'password' => 'test'
        ])->assertOk();
    }
}
