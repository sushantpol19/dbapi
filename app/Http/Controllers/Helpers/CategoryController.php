<?php

namespace App\Http\Controllers\Helpers;

use Illuminate\Http\Request;
use App\Models\Helpers\Category;
use App\Models\Companies\Company;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:api');
	}

  /*
   * To get all the categories
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company-id'))->first();
    if($company)
      $categories = $company->categories;
    else
      $categories = "";

    return response()->json([
      'data'  =>  $categories
    ], 200);
  }

  /**
   * Add a new category
   *
   * @ 
   */
  public function store(Request $request)
  {
  	$request->validate([
  		'category'	=>	'required'
  	]);

  	$category = new Category($request->only('category'));
    $category->store();

  	return response()->json([
  		'data'	=>	$category->toArray()
  	], 201);
  }

  /*
   * To fetch a single category
   *
   *@
   */
  public function show(Category $category)
  {
    return response()->json([
      'data'  =>  $category->toArray()
    ], 200);
  }

  /*
   * To update the category details
   *
   *@
   */
  public function update(Request $request, Category $category)
  {
    $request->validate([
      'category'  =>  'required'
    ]);

    $category->update($request->all());

    return response()->json([
      'data'  =>  $category->toArray()
    ], 200);  
  }
}
