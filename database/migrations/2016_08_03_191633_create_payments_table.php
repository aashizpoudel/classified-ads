<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_id');
            $table->integer('user_id');
            $table->decimal('amount');
            $table->string('payment_method');
            $table->enum('status', ['initial','pending','success','failed','declined','dispute']);
            $table->string('currency');
            $table->string('token_id');
            $table->string('card_last4');
            $table->string('card_id');
            $table->string('client_ip');
            $table->string('charge_id_or_token');
            $table->string('payer_email');
            $table->string('description');
            $table->string('local_transaction_id');
            //payment created column will be use by gateway
            $table->integer('payment_created');
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
        Schema::drop('payments');
    }
}
