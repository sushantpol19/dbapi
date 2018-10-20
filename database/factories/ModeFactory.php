<?php

use App\Models\Helpers\Mode;
use Faker\Generator as Faker;

$factory->define(Mode::class, function (Faker $faker) {
    return [
        'mode'	=>	'whatsapp'
    ];
});
