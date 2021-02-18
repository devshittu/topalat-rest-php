<?php

namespace Database\Factories;

use App\Models\TransactionLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $a = array("airtime", "databundle", "cabletv", "power");
//        $random_keys=array_rand($a);
        $arr = array(
            'agentReference' => "CT202111713618176",
            'reference' => 'CT202111713618176',
            'agentId' => 0,
            'service_type' => 'dstv',
            'email' => 'topalatonline@gmail.com',
            "smartcard_number" => "04185337013",
            "total_amount" => 0,
            "phone" => "08095619506",
            "product_code" => null,
            "product_monthsPaidFor" => null,
            "addon_code" => null,
            "addon_monthsPaidFor" => null,
            "transactionSummary" => "Transaction summary"
        );

        json_encode($arr);
        return [
            'email' => $this->faker->unique()->safeEmail,
            'reference' => Str::random(10),
            'description' => $this->faker->text(120),
            'service_category_raw' => $a[array_rand($a, 1)],
            'service_provider_raw' => Str::random(10),
            'payment_status' => 2,
            'service_render_status' => 1,
            'service_request_payload_data' => $arr,
//            'service_request_payload_data' => '{}',
        ];
    }
}
