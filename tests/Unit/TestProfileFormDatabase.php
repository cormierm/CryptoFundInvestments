<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestProfileFormDatabase extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function TestProfileFormDatabase()
    {
        $this->visit('/profile/edit')
			 ->type('test1@gmail.com', 'email')
			 ->type('Johnathan', 'first_name')
			 ->type('Fusari', 'last_name')
			 ->type('1231231234', 'phone')
			 ->press('UpdateProfile')
			 ->seeInDatabase('Users', ['FirstName' => 'Johnathan']);
    }
}
