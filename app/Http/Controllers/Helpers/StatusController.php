<?php

namespace App\Http\Controllers\Helpers;

use App\Helpers\Status;
use Illuminate\Http\Request;
use App\Models\Companies\Company;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the status
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company-id'))->first();
    if($company)
      $statuses = $company->statuses;
    else
      $statuses = "";

    return response()->json([
      'data'  =>  $statuses
    ], 200);
  }

  /*
   * To store a new status
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'status'  =>  'required'
    ]);

    $status = new Status($request->all());
    $status->store();

    return response()->json([
      'data'  =>  $status->toArray()
    ], 201);  
  }

  /*
   * To get a single status
   *
   *@
   */
  public function show(Status $status)
  {
    return response()->json([
      'data'  =>  $status->toArray()
    ], 200);
  }

  /*
   * To update status
   *
   *@
   */
  public function update(Request $request, Status $status)
  {
    $request->validate([
      'status'  =>  'required'
    ]); 

    $status->update($request->all());

    return response()->json([
      'data'  =>  $status->toArray()
    ], 200);  
  }
}
