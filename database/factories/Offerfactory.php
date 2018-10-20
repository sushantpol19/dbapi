<?php

use App\Offers\Offer;
use Faker\Generator as Faker;

$factory->define(Offer::class, function (Faker $faker) {
  return [
    'inquiry_id'  =>  '1',
    'cp_id'       =>  '1',
    'date'        =>  $faker->date
  ];
});
