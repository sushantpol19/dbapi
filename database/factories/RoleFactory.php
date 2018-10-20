<?php

use Faker\Generator as Faker;
use App\Models\Companies\Company;

$factory->define(\App\Role::class, function (Faker $faker) {
    return [
      'company_id'  =>  factory(Company::class)->create()->id,
       'role'	=>	'Admin'
    ];
});
