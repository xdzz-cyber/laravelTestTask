<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Propaganistas\LaravelPhone\PhoneNumber;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Propaganistas\LaravelPhone\Exceptions\CountryCodeException
     */
    public function definition()
    {
        $adminId = $this->faker->numberBetween(1,20);
        $savedImage = $this->faker->image(public_path("images",300,300));
        $correctPhoneOperatorCode = $this->faker->randomElement(["039", "067", "068", "096", "097", "098","050", "066", "095", "099", "063", "093", "091", "092","094"]);
        return [
            "fullname"=>"{$this->faker->firstName()} {$this->faker->lastName()}",
            "email"=>$this->faker->email(),
            "phone"=>"+38 " . "({$correctPhoneOperatorCode}){$this->faker->numerify("###-####")}", //PhoneNumber::make(, 'UA')->formatInternational(),
            //"phone"=>$this->faker->phoneNumber(),
            "salary"=>$this->faker->numberBetween(0, 500000),
            "position"=>$this->faker->randomElement(["Frontend-Developer", 'Backend-Developer', "Game-Developer", "iOS"]),
            "dateOfEmployment"=>$this->faker->dateTimeBetween("-2 years", "now"),
            "photo"=>pathinfo($savedImage, PATHINFO_FILENAME) . "." . pathinfo($savedImage, PATHINFO_EXTENSION),
            "admin_created_id" => $adminId,
            "admin_updated_id" => $adminId,
            "boss_id"=>$this->faker->numberBetween(1,20)
        ];
    }
}
