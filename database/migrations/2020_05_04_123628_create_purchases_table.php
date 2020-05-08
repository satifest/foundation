<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

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

            $table->unsignedBigInteger('purchaser_id')->index();

            $table->string('transaction_provider')->nullable();
            $table->string('transaction_id')->nullable();

            $table->string('name')->nullable();
            $table->unsignedInteger('amount')->default(0);
            $table->string('currency')->default(Config::get('satifest.currency', 'USD'));

            $table->timestamp('purchased_at');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['transaction_provider', 'transaction_id']);
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
