<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $id = DB::table('users')->insertGetId([
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'first_name' => 'admin',
            'last_name' => 'admin',
            'phone' => '1231231234'
        ]);

        DB::table('role_user')->insert([
            'user_id' => $id,
            'role_id' => 1
        ]);
        DB::table('role_user')->insert([
            'user_id' => $id,
            'role_id' => 2
        ]);
        DB::table('role_user')->insert([
            'user_id' => $id,
            'role_id' => 3
        ]);

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
