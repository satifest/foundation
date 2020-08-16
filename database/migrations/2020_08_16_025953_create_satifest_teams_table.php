<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatifestTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sf_teams', function (Blueprint $table) {
            $table->id();

            $table->string('email')->index();
            $table->unsignedBigInteger('license_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->timestamps();
            $table->timestamp('accepted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sf_teams');
    }
}
