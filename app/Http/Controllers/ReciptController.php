<?php

namespace App\Http\Controllers;

use App\Http\Requests\Recipt\CheckReciptRequest;
use Illuminate\Http\Request;

class ReciptController extends Controller
{
    public function __construct()
    {
        $this->middleware('recipt.header');
    }

    public function checkRecipt(CheckReciptRequest $request)
    {
        return response()->json(
            [
                'status' => !!rand(0, 1),
                'expire-date' => now('-6')->format('Y-m-d H:i-s'),
            ]
        );
    }
}
