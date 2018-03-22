<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestProfileFormFail extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function TestProfileFormFail()
    {
        $this->visit('/profile/edit')
			 ->type('test1@gmail.com', 'email')
			 ->press('UpdateProfile')
			 ->seePageIs('/profile/edit');
    }
}
