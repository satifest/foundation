<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatifestRepositoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sf_repositories', function (Blueprint $table) {
            $table->id();

            $table->string('provider')->default('github')->index();
            $table->string('type')->default('vcs')->index();

            $table->string('name');
            $table->string('package')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('url');

            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sf_repositories');
    }
}
