<?php

namespace App\Helpers;

use App\Models\Companies\Company;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
  protected $fillable = [
    'status'
  ];

  /*
   * A status belongs to a company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /*
   * To store a new status
   *
   *@
   */

  public function store()
  {
    if(request()->header('company-id')) {
      $company = Company::find(request()->header('company-id'));
      if($company)
        $company ? $company->statuses()->save($this) : '';
    } 

    return $this;
  }
}
