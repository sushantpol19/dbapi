<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*Auth*/
Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

/*Helpers*/
Route::resource('roles', 'Helpers\RoleController');
Route::resource('statuses', 'Helpers\StatusController');

/*Companies*/
Route::resource('companies', 'Companies\CompanyController');

/*Contacts*/
Route::resource('contacts', 'Contacts\ContactController');
/*Contact Categories*/
Route::resource('categories', 'Helpers\CategoryController');

/*Inquiries*/
Route::resource('inquiries', 'Inquiries\InquiryController');
/*Inquiry types*/
Route::resource('types', 'Helpers\TypeController');
/*Inquiry Modes*/
Route::resource('modes', 'Helpers\ModeController');
/*Inauiry Remarks*/
Route::resource('inquiries/{inquiry}/remarks', 'Inquiries\RemarkController');
/*Inquiry Offers*/
Route::resource('inquiries/{inquiry}/offers', 'Offers\OfferController');
/*Inquiry Offer Remarks*/
Route::resource('inquiries/{inquiry}/offers/{offer}/remarks', 'Offers\RemarkController');
