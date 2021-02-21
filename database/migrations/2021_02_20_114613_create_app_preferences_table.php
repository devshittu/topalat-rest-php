<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*{
          app_name: "Topalat Nigeria",
          services_rate: 80,
          services: {
            airtime: true,
            databundle: true,
            cabletv: true,
            power: true,
          },
          // announcement: false,
          announcement: {
            type: 'is-danger',
            message: "This is a note for all users to see. I hope it catches <b>attention</b>",
          },
        }*/
        Schema::create('app_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('profile_key', 30)->unique();
            $table->json('settings');
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
        Schema::dropIfExists('app_preferences');
    }
}
