<?php

namespace Tests\Unit;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testFetchItems()
    {
        $user = factory(User::class)->create();
        $item = factory(Item::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/item')
            
            ->assertStatus(200)->assertJsonStructure([
                    'data' => 
                        [0 => [
                            'name',
                            'description',
                            'qty',
                            'price',
                            'list_price',
                            'price_sold',
                            'sold_on',
                            'dimension',
                            'available',
                        ],
                    ]
                ]
            )->assertJsonCount(2);
    }
}
