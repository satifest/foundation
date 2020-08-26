<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatifestLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sf_licenses', function (Blueprint $table) {
            $table->id();

            $table->morphs('licensee');

            $table->string('name')->nullable();
            $table->string('provider');
            $table->string('uid');
            $table->string('type');

            $table->unsignedInteger('amount')->nullable();
            $table->string('currency')->nullable();

            $table->timestamp('ends_at')->nullable();

            $table->unsignedInteger('allocation')->default(0);
            $table->unsignedInteger('utilisation')->default(0);

            $table->timestamps();

            $table->index(['provider', 'uid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sf_licenses');
    }
}
