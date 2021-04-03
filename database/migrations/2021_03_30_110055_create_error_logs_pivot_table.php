<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErrorLogsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('user_id');
            $table->integer('booking_id')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->string('description');
            // $table->foreign('user_id', 'user_id_fk_1197463')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('booking_id', 'booking_id_fk_1197463')->references('id')->on('bookings')->onDelete('cascade');
            // $table->foreign('transaction_id', 'transaction_id_fk_1197463')->references('id')->on('transactions')->onDelete('cascade');
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
        Schema::dropIfExists('error_logs');
    }
}
