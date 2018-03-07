<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('currency_id');
            $table->integer('rank');
            $table->double('price_btc');
            $table->double('price_usd');
            $table->double('price_cad');
            $table->double('market_cap_usd');
            $table->double('market_cap_cad');
            $table->double('volume_usd');
            $table->double('volume_cad');
            $table->double('available_supply');
            $table->double('total_supply');
            $table->double('max_supply')->nullable();
            $table->double('percent_change_hour');
            $table->double('percent_change_day');
            $table->double('percent_change_week');
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
        Schema::dropIfExists('coin_prices');
    }
}
