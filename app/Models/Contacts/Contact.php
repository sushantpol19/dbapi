<?php

namespace App\Models\Contacts;

use App\User;
use App\Models\Helpers\Category;
use App\Models\Companies\Company;
use App\Models\Inquiries\Inquiry;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
  protected $fillable = [
  	'name', 'contact_company_name'
  ];

  /**
   * A contact belongs to a user
   *
   * @ 
   */
  public function user()
  {
  	return $this->belongsTo(User::class);
  }

  /*
   * A contact belongs to company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /**
   * TO store a contact
   *
   * @ 
   */
  public function store()
  {
    if(request()->header('company-id')) {
      $company = Company::find(request()->header('company-id'));
      if($company)
        $company ? $company->contacts()->save($this) : '';
    } 

    return $this; 
  }

  /**
   * A contact belongs to category
   *
   * @ 
   */
  public function categories()
  {
  	return $this->belongsToMany(Category::class)
  		->withTimestamps();
  }

  /*
   * Detach all categories before assigning
   *
   *@
   */
  public function detachCategories()
  {
    foreach($this->categories as $category)
    {
      $this->categories()->detach($category);
    }
  }

  /**
   * Assign a category to a contact
   *
   * @ 
   */
  public function assignCategory($category_id)
  {
  	$category = Category::where('id', '=', $category_id)->first();
    if(! $this->hasCategory($category->category)){
      $this->categories()->attach($category); 
      $this->refresh();
    }

  	return $this;
  }

  /**
   * TO check if the contact belongs to a specific category 
   *
   * @ 
   */
  public function hasCategory($category)
  {
  	return $this->categories ? in_array($category, $this->categories->pluck('category')->toArray()) : false;
  }

  /**
   * A contact can make as many inquiry as possible
   *
   * @ 
   */
  public function inquiries()
  {
  	return $this->hasMany(Inquiry::class);
  }
}
