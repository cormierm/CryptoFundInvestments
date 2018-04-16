<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
	use DatabaseMigrations;
	
    /**
     * For LoginTest to pass, credentials below must 
	 * belong to an account 
     * @return void
     */
    public function testLogin()
    {   
        $this->browse(function ($browser){
            $browser->visit('/login')
                    ->type('email', 'test@test.com')
                    ->type('password', '123456')
                    ->press('Login')
                    ->assertPathIs('/login');
        });
    }
}
