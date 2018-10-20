<?php

namespace App\Models\Helpers;

use App\User;
use App\Models\Contacts\Contact;
use App\Models\Companies\Company;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = [
  	'category'
  ];

  /**
   * A category is addby by a user and it belongs to a user
   *
   * @ 
   */
  public function user()
  {
  	return $this->belongsTo(User::class);
  }

  /*
   * A category belongs to a company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /*
   * To store a category
   *
   *@
   */
  public function store()
  {
    if(request()->header('company-id')) {
      $company = Company::find(request()->header('company-id'));
      if($company)
        $company ? $company->categories()->save($this) : '';
    } 

    return $this;
  }

  /**
   * A category belongs to many contacts
   *
   * @ 
   */
  public function contacts()
  {
  	return $this->belongsToMany(Contact::class);
  }
}
