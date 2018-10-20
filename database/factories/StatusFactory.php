<?php

use App\Helpers\Status;
use Faker\Generator as Faker;

$factory->define(Status::class, function (Faker $faker) {
  return [
    'company_id'  =>  '1',
    'status'      =>  'booked'
  ];
});
