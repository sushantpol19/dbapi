<?php

namespace App\Models\Companies;

use App\Role;
use App\User;
use App\Helpers\Status;
use App\Models\Helpers\Mode;
use App\Models\Helpers\Type;
use App\Models\Contacts\Contact;
use App\Models\Helpers\Category;
use App\Models\Inquiries\Inquiry;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  protected $fillable = [
  	'name'
  ];

  /**
   * A company belongs to many users
   *
   * @ 
   */
  public function users()
  {
  	return $this->belongsToMany(User::class);
  }

  /*
   * Helpers
   *
   *@
   */
  public function roles() { return $this->hasMany(Role::class); } 
  public function categories() { return $this->hasMany(Category::class); } 
  public function types() { return $this->hasMany(Type::class); } 
  public function modes() { return $this->hasMany(Mode::class); } 
  public function statuses() { return $this->hasMany(Status::class); } 

  /**
   * A company has many inquiries
   *
   * @ 
   */
  public function inquiries()
  {
  	return $this->hasMany(Inquiry::class)
      ->with('contact', 'inquiry_details', 'types', 'modes', 'hiring_details', 'inquiry_remarks');
  }

  /*
   * A company has many contacts
   *
   *@
   */
  public function contacts()
  {
    return $this->hasMany(Contact::class)
      ->with('categories');
  }
}
