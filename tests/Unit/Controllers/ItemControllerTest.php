<?php

namespace Tests\Unit;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use \Illuminate\Support\Str;

class ItemControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testFetchItems()
    {
        list($user, $item) = $this->makeItem();

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

    public function testFetchItem()
    {
        list($user, $item) = $this->makeItem();

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/item/'.$item->id)
            
            ->assertStatus(200)->assertJsonStructure([
                    'data' =>  [
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
            )->assertJsonCount(1);
    }

    

    public function testItemCreation()
    {
        $this->withoutMiddleware();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('POST', '/api/item', [
            'name'      => Str::random(10),
            'qty'       => '1',
            'price'      => (float) rand(1,1000),
            'list_price' => (float) rand(1000,2000),
            'dimension'  => json_encode([]),
            'available'  => true,
            'user_id'    => $user->id,
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
            'message' => 'Item Created!'
        ]);
    }

    public function testItemDeletion()
    {
        list($user, $item) = $this->makeItem();

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', '/api/item/'.$item->id)
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Item Deleted!'
            ]);
    }
}
