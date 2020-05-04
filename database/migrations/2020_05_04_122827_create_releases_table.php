<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('repository_id');
            $table->string('version');
            $table->string('checksum')->nullable();

            $table->string('title')->nullable();
            $table->longText('description')->nullable();

            $table->string('artifact_disk')->nullable();
            $table->string('artifact_url')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['repository_id', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('releases');
    }
}
