<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use App\User;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'description' => '',
        'qty'         => 1,
        'price'       => $faker->randomDigit,
        'list_price'  => $faker->randomDigit,
        'price_sold'  => false,
        'sold_on'     => null,
        'dimension'   => '',
        'available'   => 1,
        'user_id'     => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
