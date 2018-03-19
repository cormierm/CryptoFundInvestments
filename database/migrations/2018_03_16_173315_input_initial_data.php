<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InputInitialData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Roles Table
        DB::table('roles')->insert(
            ['name' => 'Trader']
        );
        DB::table('roles')->insert(
            ['name' => 'Client']
        );

        //Risks
        DB::table('risks')->insert(
            ['name' => 'Aggessive']
        );
        DB::table('risks')->insert(
            ['name' => 'Balanced']
        );
        DB::table('risks')->insert(
            ['name' => 'Conservative']
        );

        //Currency Types
        DB::table('currency_types')->insert(
            ['name' => 'Crypto']
        );
        DB::table('currency_types')->insert(
            ['name' => 'Fiat']
        );

        //Currencies
        DB::table('currencies')->insert(
            ['name'=>'CAD','symbol'=>'CAD','currency_type_id'=>2,'coin_market_cap_id'=>null]
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
