<?php

use Faker\Generator as Faker;

$userFactory = function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
};

$factory->define(\Nagy\LaravelStatus\Tests\Models\User::class, $userFactory);