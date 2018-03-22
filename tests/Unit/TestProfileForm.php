<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestProfileForm extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->visit('/profile/edit')
			 ->type('test1@gmail.com', 'email')
			 ->type('Derek', 'first_name')
			 ->type('Fusari', 'last_name')
			 ->type('1231231234')
			 ->press('UpdateProfile')
			 ->seePageIs('/profile')
    }
}
