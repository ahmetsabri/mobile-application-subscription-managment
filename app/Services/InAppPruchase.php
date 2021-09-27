<?php

namespace App\Services;

use App\Models\ClientToken;
use App\Models\Device;
use App\Repositories\InAppPurchaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

class InAppPruchase
{
    protected $request;
    private InAppPurchaseRepository $in_app_pruchase_repository;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->in_app_pruchase_repository = new InAppPurchaseRepository($this->request->client_token);
    }

    protected function getDevice(): Device
    {
        return optional(ClientToken::where('token', $this->request->client_token)->firstOrFail())->device;
    }

    public function pruchase()
    {
        $recipt_data = $this->checkRecipt();
        return $this->in_app_pruchase_repository->updateOrCreateSubscription($recipt_data);
    }
    private function checkRecipt()
    {
        $device = $this->getDevice();

        return $device->os == 'ios' ? $this->checkIosRescipt($this->request->recipt) :
            $this->checkAndroidRecipt($this->request->recipt);
    }

    protected function checkIosRescipt(string $recipt)
    {
        if (preg_match('/[13579]$/', $recipt)) {
            return ([
                'status'=> $status = !!rand(0, 1),
                'expire_at' => $status ? now('-6')->addMonth()->format('Y-m-d H:i-s') :
                    now('-6')->subMonth()->format('Y-m-d H:i-s')
            ]);
        }
    }

    protected function checkAndroidRecipt(string $recipt)
    {
        if (preg_match('/[13579]$/', $recipt)) {
            return ([
                'status'=> $status = !!rand(0, 1),
                'expire_at' => $status ? now('-6')->addMonth()->format('Y-m-d H:i-s') :
                    now('-6')->subMonth()->format('Y-m-d H:i-s')
            ]);
        }
    }
}
