<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APITest extends TestCase
{
    use DatabaseTransactions;
    public function testUserCreation()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Demo User',
            'email' => str_random(10) . '@demo.com',
            'password' => '12345',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token', 'name']
        ]);
    }

    public function testUserLogin()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => 'demo@demo.com',
            'password' => 'secret'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token']
        ]);
    }

    public function testItemFetch()
    {
        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/item')
            ->assertStatus(200)->assertJsonStructure([
                    '*' => [
                        'id',
                        'name',
                        'qty',
                        'price',
                        'list_price',
                        'dimension',
                        'available',
                        'user_id',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            );
    }

    public function testItemCreation()
    {
        $this->withoutMiddleware();

        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')->json('POST', '/api/item', [
            'name' => str_random(10),
            'qty'  => '1',
            'price' => (float) rand(1,1000),
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
        $this->withoutMiddleware();

        $user = \App\User::find(1);

        $item = \App\Item::create(['name' => 'To be deleted']);

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', '/api/item/{$item->id}')
            ->assertStatus(200)->assertJson([
            'status' => true,
            'message' => 'Item Deleted'
        ]);
    }
}
