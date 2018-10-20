<?php

use Faker\Generator as Faker;
use App\Models\Companies\Company;

$factory->define(Company::class, function (Faker $faker) {
    return [
       'name'	=>	'AAIBUZZ'
    ];
});
