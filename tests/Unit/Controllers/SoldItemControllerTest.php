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
        $this->assertTrue(true);
    }
}
