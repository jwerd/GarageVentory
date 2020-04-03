<?php

namespace Tests;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected function makeItem()
    {
        $user = factory(User::class)->create();
        $item = factory(Item::class)->create([
            'user_id' => $user->id,
        ]);

        return [$user,$item];
    }
}
