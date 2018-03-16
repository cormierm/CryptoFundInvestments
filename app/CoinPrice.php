<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoinPrice extends Model
{
    protected $fillable = ['currency_id', 'rank', 'price_btc', 'price_usd', 'price_cad', 'market_cap_usd',
        'market_cap_cad', 'volume_usd', 'volume_cad', 'available_supply', 'total_supply', 'max_supply',
        'percent_change_hour', 'percent_change_day', 'percent_change_week'];
}
