<?php

namespace Database\Factories;

use App\Models\BusinessCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusinessCategory::class;
        
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (BusinessCategory::all()->last() !== null) {
            $user_id = BusinessCategory::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }
        
        $array = array(0, 1);
        return [
            'user_id' => $user_id,
            'artisan' => $this->faker->randomElement($array),
            'technician' => $this->faker->randomElement($array),
            'seller' => $this->faker->randomElement($array),
            'spare_part_seller' => $this->faker->randomElement($array),
            'business_name' => $this->faker->company,
            'business_description' => $this->faker->text,
            'business_page' => $this->faker->url,
            'views' => $this->faker->numberBetween(0, 1000),
            'advertise' => $this->faker->randomElement($array),
            'created_at' => $this->faker->dateTimeBetween('-3 months')
        ];
    }
}
