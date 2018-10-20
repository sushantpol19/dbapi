<?php

namespace App\Http\Controllers\Contacts;

use Illuminate\Http\Request;
use App\Models\Contacts\Contact;
use App\Models\Companies\Company;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
  public function __construct()
  {
  	$this->middleware(['auth:api', 'throttle:500,1']);
  }

  /*
   * To get all the contacts
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company-id'))->first();
    if($company)
      $contacts = $company->contacts;
    else
      $contacts = "";

    return response()->json([
      'data'  =>  $contacts
    ], 200);
  }

  /**
   * To store a new contact
   *
   * @ 
   */
  public function store(Request $request)
  { 
  	$request->validate([
  		'name'	=>	'required', 
  		'contact_company_name'	=>	'required',
  		'category_ids'			=>	'required'
  	]);

  	// To store a new contact
  	$contact = new Contact(
  		$request->only('name', 'contact_company_name')
  	);
		$contact->store();  	 

    foreach($request->category_ids as $category_id) { 
      // To assign a category
      $contact->assignCategory($category_id);
    }

		// To store morph contact phone
		// TO store morph contact email

  	return response()->json([
  		'data'	=>	$contact->toArray()
  	], 201);
  }

  /*
   * TO fetch a single contact
   *
   *@
   */
  public function show(Contact $contact)
  {
    $company = Company::where('id', '=', request()->header('company-id'))->first();
    if($company){
      $contact = $company->contacts->find($contact->id);
    }
    else
      $contacts = "";

    return response()->json([
      'data'  =>  $contact->toArray()
    ], 200);
  }

  /*
   * To update a contact
   *
   *@
   */
  public function update(Request $request, Contact $contact)
  {
    $request->validate([
      'name'  =>  'required', 
      'contact_company_name'  =>  'required',
      'category_ids'  =>  'required'
    ]); 

    $contact->update($request->all()); 

    $contact->detachCategories();
    $contact->refresh();
    foreach($request->category_ids as $category_id) {
      // To assign a category
      $contact->assignCategory($category_id);
    } 

    $contact->refresh(); 
    return response()->json([
      'data'  =>  $contact->toArray()
    ], 200);
  }
}
