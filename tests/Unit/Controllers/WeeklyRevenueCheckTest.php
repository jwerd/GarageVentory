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
        $this->assertTrue(true);
    }
}
