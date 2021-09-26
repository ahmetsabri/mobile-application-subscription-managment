<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientToken extends Model
{
    use HasFactory;
    protected $fillable = ['token', 'device_uid'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
