<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $a=array("airtime","databundle","cabletv","power");
        return [

            'full_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'subject' =>  $a[array_rand($a,1)].' '.$this->faker->sentence(4),
            'phone' => $this->faker->phoneNumber,
            'message' => $this->faker->paragraph(5),
        ];
    }
}
