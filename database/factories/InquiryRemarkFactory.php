<?php

use Faker\Generator as Faker;
use App\Models\Inquiries\InquiryRemark;

$factory->define(InquiryRemark::class, function (Faker $faker) {
  return [
    'inquiry_id'    =>  '1',  
    'date'          => '04-5-1992',
    'remark'        =>  'Completed'
  ];
});
