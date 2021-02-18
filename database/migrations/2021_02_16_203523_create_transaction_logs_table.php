<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

define('DBCV_PAYMENT_SERVICE_STATUS_UNPROCESSED', 1);
define('DBCV_PAYMENT_SERVICE_STATUS_PROCESSED', 2);
define('DBCV_PAYMENT_SERVICE_STATUS_ERROR', 3);

define('PAYMENT_STATUSES', [DBCV_PAYMENT_SERVICE_STATUS_UNPROCESSED, DBCV_PAYMENT_SERVICE_STATUS_PROCESSED, DBCV_PAYMENT_SERVICE_STATUS_ERROR]);


        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('reference')->unique();
            $table->string('description')->nullable();
            $table->string('service_category_raw')->nullable();
            $table->string('service_provider_raw')->nullable();
            $table->set('payment_status', PAYMENT_STATUSES)->default(DBCV_PAYMENT_SERVICE_STATUS_UNPROCESSED);
            $table->set('service_render_status', PAYMENT_STATUSES)->default(DBCV_PAYMENT_SERVICE_STATUS_UNPROCESSED);
            $table->json('service_request_payload_data');
//            $table->string('');
//            $table->timestamps();

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
