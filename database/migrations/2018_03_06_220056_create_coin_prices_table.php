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
            $table->integer('rank')->nullable()->default(null);
            $table->double('price_btc')->nullable()->default(null);
            $table->double('price_usd')->nullable()->default(null);
            $table->double('price_cad')->nullable()->default(null);
            $table->double('market_cap_usd')->nullable()->default(null);
            $table->double('market_cap_cad')->nullable()->default(null);
            $table->double('volume_usd')->nullable()->default(null);
            $table->double('volume_cad')->nullable()->default(null);
            $table->double('available_supply')->nullable()->default(null);
            $table->double('total_supply')->nullable()->default(null);
            $table->double('max_supply')->nullable()->default(null);
            $table->double('percent_change_hour')->nullable()->default(null);
            $table->double('percent_change_day')->nullable()->default(null);
            $table->double('percent_change_week')->nullable()->default(null);
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
