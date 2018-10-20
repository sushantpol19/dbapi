<?php

namespace App\Http\Controllers\Helpers;

use App\Models\Helpers\Mode;
use Illuminate\Http\Request;
use App\Models\Companies\Company;
use App\Http\Controllers\Controller;

class ModeController extends Controller
{
  public function __construct()
  {
  	$this->middleware('auth:api');
  }

  /*
   * To get all the modes
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company-id'))->first();
    if($company)
      $modes = $company->modes;
    else
      $modes = "";

    return response()->json([
      'data'  =>  $modes
    ], 200);
  }

  /**
   * To store a new mode
   *
   * @ 
   */
  public function store(Request $request)
  {
  	$request->validate([
  		'mode'	=>	'required'
  	]);

  	$mode = new Mode($request->only('mode'));
  	$mode->store();

  	return response()->json([
  		'data'	=>	$mode->toArray()
  	], 201);
  }

  /*
   * To fetch a single mode
   *
   *@
   */
  public function show(Mode $mode)
  {
    return response()->json([
      'data'  =>  $mode->toArray()
    ], 200);
  }

  /*
   * To update a mode
   *
   *@
   */
  public function update(Request $request, Mode $mode)
  {
    $request->validate([
      'mode'  =>  'required'
    ]); 

    $mode->update($request->only('mode'));

    return response()->json([
      'data'  =>  $mode->toArray()
    ], 200);  
  }
}
