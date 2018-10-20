<?php

namespace App\Http\Controllers\Offers;

use App\Offers\Offer;
use App\Offers\OfferRemark;
use Illuminate\Http\Request;
use App\Models\Inquiries\Inquiry;
use App\Http\Controllers\Controller;

class RemarkController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the remarks of an offer
   *
   *@
   */
  public function index($inquiry, Offer $offer)
  {
    $remarks = $offer->offer_remarks;

    return response()->json([
      'data'  =>  $remarks
    ]);
  }

  /*
   * To store a new remark
   *
   *@
   */
  public function store(Request $request, $inquiry, Offer $offer)
  {
    $request->validate([
      'date'  =>  'required',
      'remark'  =>  'required'
    ]);

    $remark = new OfferRemark($request->all());
    $offer->offer_remarks()->save($remark);

    return response()->json([
      'data'  =>  $remark->toArray()
    ], 201); 
  }

  /*
   * To get a single remark
   *
   *@
   */
  public function show(Inquiry $inquiry, Offer $offer, OfferRemark $remark)
  {
    return response()->json([
      'data'  =>  $remark->toArray()
    ]);
  }

  /*
   * To update a remark
   *
   *@
   */
  public function update(Request $request, Inquiry $inquiry, Offer $offer, OfferRemark $remark)
  {
    $request->validate([
      'date'  =>  'required',
      'remark'  =>  'required'
    ]);

    $remark->update($request->all());

    return response()->json([
      'data'  =>  $remark->toArray()
    ]);
  }
}

