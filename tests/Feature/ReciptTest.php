<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReciptTest extends TestCase
{
    public function testCheckReciptSuccessIfEndsWithOdd()
    {
        $this->getJson("/api/check-recipt?platform=ios&recipt=1231", [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(200);
    }

    public function testFailIfNotEndsWithOdd()
    {
        $response = $this->getJson("/api/check-recipt?platform=ios&recipt=1238", [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(422);

        $response->assertJsonValidationErrors('recipt');
    }

    public function testFailWithoutHeader()
    {
        $response = $this->getJson("/api/check-recipt?platform=ios&recipt=1238")->assertStatus(422);
        $response->assertSee('username or password');
    }
}
