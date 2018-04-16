<?php

namespace Tests\Feature;

use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestProfileFormDatabase extends BaseTestCase
{
    public function TestProfileFormDatabase()
    {
        $this->visit('/login')
			 ->type('dummyEmail123@gmail.com', 'email')
			 ->type('123456', 'password')
			 ->press('Login')
			 ->visit('/profile/edit')
			 ->type('test1@gmail.com', 'email')
			 ->type('Johnathan', 'first_name')
			 ->type('Smith', 'last_name')
			 ->type('1231231234', 'phone')
			 ->press('Update Profile')
			 ->seeInDatabase('Users', ['FirstName' => 'Johnathan']);
    }
}
