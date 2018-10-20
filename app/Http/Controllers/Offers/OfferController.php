<?php

namespace App\Http\Controllers\Offers;

use App\Offers\Offer;
use Illuminate\Http\Request;
use App\Models\Inquiries\Inquiry;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To fetch all the offers
   *
   *@
   */
  public function index(Inquiry $inquiry)
  {
    $offers = $inquiry->offers;

    return response()->json([
      'data'  =>  $offers
    ]);
  }

  /*
   * To store a new offer
   *
   *@
   */
  public function store(Request $request, Inquiry $inquiry)
  {
    $request->validate([
      'date'  =>  'required',
      'offerDetails.type'       =>  'required',
      'offerDetails.capacity'   =>  'required',
      'offerModeIds'=>  'required',
      'offerStatusIds'=>  'required'
    ]);

    // Save Offer
    $offer = new Offer($request->all());
    $inquiry->offers()->save($offer);

    // Save Inquiry details 
    if($request->offerDetails)
      $offer->addDetails(request()->offerDetails); 

    $offer->detachModes();
    if($request->offerModeIds)
      foreach($request->offerModeIds as $offerModeId) {
        // Save offer mode
        $offer->assignMode($offerModeId); 
      } 

    $offer->detachStatuses();
    if($request->offerStatusIds)
      foreach(request()->offerStatusIds as $offerStatusId) {
        // Save offer Status
        $offer->assignStatus($offerStatusId); 
      } 

    // dd($inquiry->offers()->find($offer->id)->toArray());

    return response()->json([
      'data' =>  $inquiry->offers()->find($offer->id)->toArray()
    ], 201);
  }

  /*
   * To fetch a single offer
   *
   *@
   */
  public function show(Inquiry $inquiry, Offer $offer)
  {
    $offer = $inquiry->offers()->find($offer->id);

    return response()->json([
      'data'  =>  $offer->toArray()
    ], 200);
  }

  /*
   * To update a offer
   *
   *@
   */
  public function update(Request $request, Inquiry $inquiry, Offer $offer)
  {
    $request->validate([
      'date'  =>  'required',
      'offerDetails.type'       =>  'required',
      'offerDetails.capacity'   =>  'required',
      'offerModeIds'=>  'required',
      'offerStatusIds'=>  'required'
    ]); 
    $offer->update($request->all()); 

    // Save Inquiry details 
    if($request->offerDetails)
      $offer->addDetails(request()->offerDetails); 

    $offer->detachModes();
    if($request->offerModeIds)
      foreach($request->offerModeIds as $offerModeId) {
        // Save offer mode
        $offer->assignMode($offerModeId); 
      } 

    $offer->detachStatuses();
    if($request->offerStatusIds)
      foreach(request()->offerStatusIds as $offerStatusId) {
        // Save offer Status
        $offer->assignStatus($offerStatusId); 
      } 

    // dd($inquiry->offers()->find($offer->id)->toArray());

    return response()->json([
      'data'  =>  $offer->toArray()
    ], 200);  
  }
}
