<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FundsPageTest extends TestCase
{
    /**
     * A basic test to see if a link works
     *
     * @return void
     */
    public function testFundsPage()
    {
        $this->visit('/login')
			 ->type('dummyEmail123@gmail.com', 'email')
			 ->type('123456', 'password')
			 ->press('Login')
			 ->visit('/')
			 ->click('Funds')
			 ->seePageIs('funds') 
    }
}
