<?php

use App\Offers\OfferRemark;
use Faker\Generator as Faker;

$factory->define(OfferRemark::class, function (Faker $faker) {
  return [
    'offer_id'  =>  '1',
    'date'      =>  '04-05-1992',
    'remark'    =>  'Hired'
  ];
});
