<?php

namespace App\Http\Controllers\Inquiries;

use Illuminate\Http\Request;
use App\Models\Companies\Company;
use App\Models\Inquiries\Inquiry;
use App\Http\Controllers\Controller;

class InquiryController extends Controller
{
  public function __construct()
  {
  	$this->middleware('auth:api');
  }

  /*
   * To get all the inquiries
   *
   *@
   */
  public function index()
  { 
    $company = Company::where('id', '=', request()->header('company-id'))->first();
    if($company)
      $inquiries = $company->inquiries;
    else
      $inquiries = "";

    return response()->json([
      'data'  =>  $inquiries
    ], 200);
  }

  /**
   * TO store a new inquiry
   *
   * @ 
   */
  public function store(Request $request)
  {
  	$request->validate([ 
  		'contact_id'	=>	'required',
  		'date'				=>	'required',
  		'inquiryDetails.type'				=>	'required',
  		'inquiryDetails.capacity'		=>	'required',
  		'inquiryTypeIds'=>	'required',
  		'inquiryModeIds'=>	'required'
  	]);

  	// Save the inquiry
  	$inquiry = new Inquiry($request->only('contact_id', 'date', 'cp_id'));
  	$inquiry->store();

    $company = Company::where('id', '=', request()->header('company-id'))->first();
    // dd($company->inquiries()->find($inquiry->id)->toArray());

  	return response()->json([
  		'data'	=>	$company->inquiries()->find($inquiry->id)->toArray()
  	], 201);
  }

  /*
   * To fetch a single inquiry
   *
   *@
   */
  public function show(Inquiry $inquiry)
  {
    $company = Company::where('id', '=', request()->header('company-id'))->first();
    $inquiry = optional($company->inquiries)->find($inquiry->id);

    return response()->json([
      'data'  =>  $inquiry->toArray()
    ], 200); 
  }

  /*
   * To update an inquiry
   *
   *@
   */
  public function update(Request $request, Inquiry $inquiry)
  {
    $request->validate([
      'contact_id'      =>  'required',
      'date'            =>  'required',
      'inquiryDetails.type'       =>  'required',
      'inquiryDetails.capacity'   =>  'required',
      'inquiryTypeIds'  => 'required',
      'inquiryModeIds'  => 'required'
    ]);

    $inquiry->store();

    $company = Company::where('id', '=', request()->header('company-id'))->first();
    // dd($company->inquiries()->find($inquiry->id)->toArray());

    return response()->json([
      'data'  =>  $company->inquiries()->find($inquiry->id)->toArray()
    ], 200);
  }
}
