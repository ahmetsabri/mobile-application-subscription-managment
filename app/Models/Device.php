<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['uid', 'app_id', 'language', 'os'];

    public function token()
    {
        return $this->hasOne(ClientToken::class);
    }

    public function generateToken()
    {
        return $this->token()->firstOrCreate(['token'=>$this->hashToken()]);
    }

    public function getToken()
    {
        return Cache::get($this->hashToken());
    }

    public function hashToken()
    {
        return hash('sha256', "{$this->uid}-{$this->app_id}");
    }
}
