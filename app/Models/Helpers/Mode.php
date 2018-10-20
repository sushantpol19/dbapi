<?php

namespace App\Models\Helpers;

use App\User;
use App\Models\Companies\Company;
use App\Models\Inquiries\Inquiry;
use Illuminate\Database\Eloquent\Model;

class Mode extends Model
{
  protected $fillable = [
  	'mode'
  ];

  /**
   * A mode belongs to user
   *
   * @ 
   */
  public function user()
  {
  	return $this->belongsTo(User::class);
  }

  /*
   * A mode belongs to a company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /**
   * To store a new mode
   *
   * @ 
   */
  public function store()
  {
  	if(request()->header('company-id')) {
      $company = Company::find(request()->header('company-id'));
      if($company)
        $company ? $company->modes()->save($this) : '';
    } 

    return $this;
  }

  /**
   * A mode can belong to many inquiries (whatsapp, email)
   *
   * @ 
   */
  public function inquiries()
  {
  	return $this->belongsToMany(Inquiry::class)
  		->withTimestamps();
  }
}
