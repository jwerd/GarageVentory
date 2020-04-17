<?php

namespace Tests\Browser;

use App\Item;
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

    public function testGuestCantSeeItems()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->assertSee('Login')
                    ->assertPathIs('/login');
        });
    }

    public function testLoadsItems()
    {
        $this->markTestIncomplete();
        
        $user = factory(User::class)->create();

        $item = factory(Item::class)->create([
            'user_id' => $user->id,
        ]);

        $this->browse(function ($browser) use ($user, $item) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->assertSee($item->name)
                    ->assertPathIs('/');
        });
    }

    public function testCreateItem()
    {
        $user = factory(User::class)->create();
    }
    
}
