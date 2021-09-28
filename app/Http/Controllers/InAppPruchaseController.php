<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\InAppPruchaseRequest;
use App\Repositories\SubscriptionRepository;
use App\Services\InAppPruchase;
use Illuminate\Http\Request;

class InAppPruchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('recipt.header');
    }

    public function pruchase(InAppPruchaseRequest $request, InAppPruchase $in_app_pruchase)
    {
        $subscription = $in_app_pruchase->pruchase($request);

        return response()->json(compact('subscription'));
    }
}
