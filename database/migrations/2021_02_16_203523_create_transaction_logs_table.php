<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateTransactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
# Payment statuses on the TransactionLog model



        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('reference')->unique();
            $table->string('description')->nullable();
            $table->string('service_category_raw')->nullable();
            $table->string('service_provider_raw')->nullable();
//            $table->set('payment_status', Config::get('constants.service_status'))->default(Config::get('constants.service_status.PENDING'));
//            $table->set('service_render_status', Config::get('constants.service_status'))->default(Config::get('constants.service_status.PENDING'));
            $table->json('service_request_payload_data');
//            $table->string('');
//            $table->timestamps();

            $table->tinyInteger('payment_status')->default(Config::get('constants.service_status.PENDING'));
            $table->tinyInteger('service_render_status')->default(Config::get('constants.service_status.PENDING'));
            $table->unsignedInteger('created_at');
            $table->unsignedInteger('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_logs');
    }
}
