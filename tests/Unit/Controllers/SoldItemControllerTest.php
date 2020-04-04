<?php

namespace Tests\Unit;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use \Illuminate\Support\Str;

class SoldItemControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testMarkItemSold()
    {
        list($user, $item) = $this->makeItem();

        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/sold/'.$item->id)
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Item Updated!'
            ]);
    }
}
