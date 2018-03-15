<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fund_id');
            $table->integer('sell_currency_id')->nullable();
            $table->double('sell_amount')->default(0);
            $table->double('sell_price')->default(0);
            $table->integer('buy_currency_id')->nullable();
            $table->double('buy_amount')->default(0);
            $table->double('buy_price')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
