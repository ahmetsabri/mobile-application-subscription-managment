<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subscription\CheckSubscriptionRequest;
use Illuminate\Support\Facades\Cache;

class SubscriptionController extends Controller
{
    public function __invoke(CheckSubscriptionRequest $request)
    {
        return response()->json([
            'status' => Cache::get("sub-{$request->client_token}"),
        ]);
    }
}
