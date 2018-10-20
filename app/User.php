<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/*Traits*/
use App\Traits\RoleTrait;

/*Models*/
use App\Models\Companies\Company;
use App\Models\Contacts\Contact;
use App\Models\Helpers\Category;
use App\Models\Helpers\Type;
use App\Models\Helpers\Mode;

class User extends Authenticatable
{
  use RoleTrait, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];

  /**
   * Generate and save the api token
   *
   * @ 
   */
  public function generateToken()
  {
  	$this->api_token = str_random(60);
  	$this->save();

  	return $this->api_token;
  } 

  /**
   * A user belongs to many companies
   *
   * @ 
   */
  public function companies()
  {
  	return $this->belongsToMany(Company::class);
  }

  /**
   * Attach a company to a user
   *
   * @ 
   */
  public function hasCompany($company)
  {
  	return $this->companies()->attach($company);
  }

  /**
   * A user has many contacts
   *
   * @ 
   */
  public function contacts()
  {
  	return $this->hasMany(Contact::class);
  }

  /**
   * A user can add many contact categories
   *
   * @ 
   */
  public function categories()
  {
  	return $this->hasMany(Category::class);
  }

  /**
   * A user can add inquiry types
   *
   * @ 
   */
  public function types()
  {
  	return $this->hasMany(Type::class);
  }

  /**
   * A user can many modes (whatsapp, mail)
   *
   * @ 
   */
  public function modes()
  {
  	return $this->hasMany(Mode::class);
  }
}
