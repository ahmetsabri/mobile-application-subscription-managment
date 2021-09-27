<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'uid'=> Str::random(5),
        'app_id'=> Str::random(64),
        'language'=> $this->faker->languageCode(),
        'os' => $this->faker->randomElement(['android', 'ios'])
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Device $device) {
            $device->generateToken();
        });
    }
}
