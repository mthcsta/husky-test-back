<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Delivery;


class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('deliveryman_id')->nullable();
            $table->enum('status', Delivery::getStatusArray())->default(Delivery::getStatusDefault());
            $table->unsignedBigInteger('collect_point_id');
            $table->unsignedBigInteger('destination_point_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('deliveryman_id')->references('id')->on('deliverymen');
            $table->foreign('collect_point_id')->references('id')->on('points');
            $table->foreign('destination_point_id')->references('id')->on('points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
