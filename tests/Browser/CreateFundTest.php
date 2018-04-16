<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateFundTest extends DuskTestCase
{
    /**
     * Create Fund Test -- Account info must be correct and for a trader.
     *
     * @return void
     */
    public function testCreateFund()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
					->type('email', 'test@test.com')
                    ->type('password', '123456')
                    ->press('Login')
					->visit('/funds/create')
					->type('name', 'New Fund')
					->type('description', 'Test fund')
                    ->press('Create Fund')
					->assertPathIs('/funds');
        });
    }
}
