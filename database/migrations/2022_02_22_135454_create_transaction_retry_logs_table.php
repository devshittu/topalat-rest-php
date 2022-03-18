<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionRetryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('transaction_retry_logs')) {
            Schema::create('transaction_retry_logs', function (Blueprint $table) {
                $table->id();
                $table->string('reference')->unique()->index();
                $table->foreign('reference')->references('reference')->on('transaction_logs')->onDelete('cascade');
                $table->json('service_request_payload_data');
                $table->tinyInteger('service_render_status')->default(Config::get('constants.service_status.PENDING'));
                $table->unsignedInteger('created_at');
                $table->unsignedInteger('updated_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_retry_logs');
    }
}
