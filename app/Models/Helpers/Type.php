<?php

namespace App\Models\Helpers;

use App\User;
use App\Models\Companies\Company;
use Illuminate\Database\Eloquent\Model;
Use App\Models\Inquiries\Inquiry;

class Type extends Model
{
  protected $fillable = [
  	'type'
  ];

  /**
   * A type belongs to user
   *
   * @ 
   */
  public function user()
  {
  	return $this->belongsTo(User::class);
  }

  /*
   * A type belongs to a company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /**
   * TO store a new type
   *
   * @ 
   */
  public function store()
  {
  	if(request()->header('company-id')) {
      $company = Company::find(request()->header('company-id'));
      if($company)
        $company ? $company->types()->save($this) : '';
    } 

    return $this;
  }

  /**
   * A type (hire, sale) belongs to many inquiries
   *
   * @ 
   */
  public function inquiries()
  {
  	return $this->belongsToMany(Inquiry::class);
  }
}
