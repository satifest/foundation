<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('purchaser_id');
            $table->unsignedBigInteger('plan_id');

            $table->string('name')->nullable();
            $table->unsignedInteger('amount')->default(0);
            $table->string('currency')->default(Config::get('satifest.currency', 'USD'));

            $table->timestamp('purchased_at');
            $table->timestamp('revoked_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['purchaser_id', 'plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
