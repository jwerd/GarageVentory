<?php

namespace Tests\Unit;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use \Illuminate\Support\Str;

class WeeklyRevenueCheckTest extends TestCase
{
    use DatabaseTransactions;

    public function testGet()
    {
        $price_sold = 300;
        $user = factory(User::class)->create();
        // Mark product sold
        $item = factory(Item::class)->create([
            'user_id'    => $user->id,
            'price'      => 100,
            'price_sold' => $price_sold,
            'available'  => false,
        ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/weeklyRevenueCheck')
            ->assertStatus(200)->assertJson([
                'total' => $price_sold,
            ]);
    }
}
