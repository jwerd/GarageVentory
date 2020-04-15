<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SmokeTest extends DuskTestCase
{
    use DatabaseTransactions;

    public function testAppLoads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('GarageVentory');
        });
    }

    public function testUserCanLogin()
    {
        $user = factory(User::class)->create();

        dump($user);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->waitForText('Login')
                    ->sleep(5)
                    ->type('email', $user->email)
                    //->type('password', 'password')
                    //->press('Login')
                    ->assertPathIs('/');
        });
    }

}
