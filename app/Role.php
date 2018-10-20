<?php

namespace App;

use App\Models\Companies\Company;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $fillable = [
		'role'
	];

  /**
   * A role belongs to many user
   *
   * @ 
   */
  public function user()
  {
  	return $this->belongsToMany(User::class);
  }

  /*
   * A role belongs to a company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /*
   * To store a new role
   *
   *@
   */
  public function store()
  {
    if(request()->header('company-id')) {
      $company = Company::find(request()->header('company-id'));
      if($company)
        $company ? $company->roles()->save($this) : '';
    } 

    return $this;
  }
}
