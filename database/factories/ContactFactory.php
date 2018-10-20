<?php

use Faker\Generator as Faker;
use App\Models\Contacts\Contact;
use App\Models\Companies\Company;

$factory->define(Contact::class, function (Faker $faker) {
    return [
      'company_id'  =>  factory(Company::class)->create()->id,
      'name'	=>	'Vijay',
			'contact_company_name'	=>	'aaibuzz' 
    ];
});
