<?php

namespace Tests\Unit;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use \Illuminate\Support\Str;

class SinglePageControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testGet()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/')
            ->assertStatus(200);
    }
}
