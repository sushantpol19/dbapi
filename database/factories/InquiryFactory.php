<?php

use Faker\Generator as Faker;
use App\Models\Inquiries\Inquiry;

$factory->define(Inquiry::class, function (Faker $faker) {
    return [
        'contact_id'	=>	'1',
        'company_id'	=>	'1',
        'cp_id'      =>  '1',
        'date'				=>	'04-05-1992'
    ];
});
