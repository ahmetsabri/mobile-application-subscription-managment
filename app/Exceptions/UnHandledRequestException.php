<?php

namespace App\Exceptions;

use Exception;

class UnHandledRequestException extends Exception
{
    public function report()
    {
    }

    public function render()
    {
        return response()->json($this->message, 422);
    }
}
