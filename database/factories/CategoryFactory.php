<?php

use Faker\Generator as Faker;

/*Models*/
use App\Models\Helpers\Category;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'category'	=>	'client'
    ];
});
