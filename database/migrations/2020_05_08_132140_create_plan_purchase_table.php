<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_purchase', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('purchase_id');

            $table->timestamp('revoked_at')->nullable();

            $table->index(['plan_id', 'purchase_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_purchase');
    }
}
