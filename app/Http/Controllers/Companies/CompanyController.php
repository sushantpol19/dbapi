<?php

namespace App\Http\Controllers\Companies;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/*Models*/
use App\Models\Companies\Company;

class CompanyController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:api');
	}

	/**
	 * To get all the companies of an user
	 *
	 * @ 
	 */
	public function index()
	{
		$user = \Auth::guard('api')->user();
		$companies = $user->companies;

		return response()->json([
			'data'	=>	$companies->toArray()
		], 200);
	}

  /**
   * To store a new company
   *
   * @ 
   */
  public function store(Request $request)
  {
  	$request->validate([
  		'name'	=>	'required'
  	]);

  	$company = new Company($request->only('name'));
  	$company->save(); 

  	$user = \Auth::guard('api')->user();
  	$user->hasCompany($company);

  	return response()->json([
  		'data'	=>	$company->toArray()
  	], 201);
  }

  /*
   * To fetch a single company
   *
   *@
   */
  public function show(Company $company)
  {
    return response()->json([
      'data'  =>  $company->toArray()
    ], 200);
  }

  /**
   * To update a company
   *
   * @ 
   */
  public function update(Request $request, Company $company)
  {
  	$request->validate([
  		'name'	=>	'required'
  	]);

  	$company->update($request->all());

  	return response()->json([
  		'data'	=>	$company->toArray()
  	], 200);
  }
}
