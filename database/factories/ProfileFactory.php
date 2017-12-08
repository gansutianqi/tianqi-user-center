<?php

use Faker\Generator as Faker;

$factory->define(\App\Profile::class, function (Faker $faker) {
    return [
        'avatar_url' => $faker->url,
        'location' => $faker->address,
        'website' => $faker->url,
        'bio' => $faker->text(),
    ];
});
