<?php

namespace App\Http\Controllers\Helpers;

use App\Role;
use Illuminate\Http\Request;
use App\Models\Companies\Company;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To fetch all the roles
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company-id'))->first();
    if($company)
      $roles = $company->roles;
    else
      $roles = "";

    return response()->json([
      'data'  =>  $roles
    ], 200);
  }

  /*
   * To store a new role
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'role'  =>  'required'
    ]);

    $role = new Role($request->only('role'));
    $role->store(); 

    return response()->json([
      'data'  =>  $role->toArray()
    ], 201);  
  }

  /*
   * To get a single role
   *
   *@
   */
  public function show(Role $role)
  {
    return response()->json([
      'data'  =>  $role->toArray()
    ], 200);
  }

  /*
   * 
   *
   *@
   */
  public function update(Request $request, Role $role)
  {
    $request->validate([
      'role'  =>  'required'
    ]);

    $role->update($request->only('role'));

    return response()->json([
      'data'  =>  $role->toArray()
    ], 200);
  }
}
