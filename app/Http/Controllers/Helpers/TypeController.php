<?php

namespace App\Http\Controllers\Helpers;

use App\Models\Helpers\Type;
use Illuminate\Http\Request;
use App\Models\Companies\Company;
use App\Http\Controllers\Controller;

class TypeController extends Controller
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
      $types = $company->types;
    else
      $types = "";

    return response()->json([
      'data'  =>  $types
    ], 200);
  }

  /**
   * To create a new inquiry type
   *
   * @ 
   */
  public function store(Request $request)
  {
  	$request->validate([
  		'type'	=>	'required'
  	]);

  	$type = new Type($request->all());
  	$type->store();

  	return response()->json([
  		'data'	=>	$type->toArray()
  	], 201);
  }

  /*
   * TO show a new type
   *
   *@
   */
  public function show(Type $type)
  {
    return response()->json([
      'data'  =>  $type->toArray()
    ], 200);
  }

  /*
   * To update a type
   *
   *@
   */
  public function update(Request $request, Type $type)
  {
    $request->validate([
      'type'  =>'required'
    ]);

    $type->update($request->only('type'));

    return response()->json([
      'data'  => $type->toArray()
    ], 200);
  }
}
