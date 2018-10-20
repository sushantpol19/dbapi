<?php

use App\Models\Helpers\Type;
use Faker\Generator as Faker;

$factory->define(Type::class, function (Faker $faker) {
    return [
        'type'	=>	'hire'
    ];
});
