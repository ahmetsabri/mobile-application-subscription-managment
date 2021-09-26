<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Device extends Model
{
    use HasFactory;

    // protected $primaryKey = 'uid';

    // protected $keyType = 'string';

    // public $incrementing = false;

    protected $fillable = ['uid', 'app_id', 'language', 'os'];



    public function tokens()
    {
        return $this->hasMany(ClientToken::class);
    }

    public function generateToken()
    {
        return $this->tokens()->firstOrCreate(['token'=>$this->hashToken()]);
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
