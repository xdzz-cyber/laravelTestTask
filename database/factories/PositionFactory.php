<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Position::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $adminId = $this->faker->numberBetween(1,20);
        return [
            "name"=>$this->faker->randomElement(["Frontend-Developer", 'Backend-Developer', "Game-Developer", "iOS"]),
            "admin_created_id" => $adminId,
            "admin_updated_id" => $adminId,
        ];
    }
}
