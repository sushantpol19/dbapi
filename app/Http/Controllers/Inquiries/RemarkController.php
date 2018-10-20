<?php

namespace App\Http\Controllers\Inquiries;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/*Models*/
use App\Models\Inquiries\Inquiry;
use App\Models\Inquiries\InquiryRemark;

class RemarkController extends Controller
{
  public function __construct()
  {
  	$this->middleware('auth:api');
  }

  /*
   * To get all the remarks of a inquiry
   *
   *@
   */
  public function index(Inquiry $inquiry)
  {
    return response()->json([
      'data'  =>  $inquiry->inquiry_remarks
    ], 200);
  }

  /**
   * To store a new remark
   *
   * @ 
   */
  public function store(Request $request, Inquiry $inquiry)
  {
  	$request->validate([
  		'remark'	=> 'required',
  		'date'		=>	'required'
  	]);

  	$remark = new InquiryRemark($request->only('date', 'remark'));
  	$inquiry->inquiry_remarks()->save($remark);

  	return response()->json([
  		'data'	=>	$remark->toArray()
  	], 201);
  }

  /*
   * To get a single remark
   *
   *@
   */
  public function show(Inquiry $inquiry, $remark)
  {
    $remark = InquiryRemark::where('id', '=', $remark)->first();

    return response()->json([
      'data'  =>  $remark->toArray()
    ], 200);
  }

  /*
   * To update a remark
   *
   *@
   */
  public function update(Request $request, Inquiry $inquiry, $remark)
  {
    $request->validate([
      'remark'  =>  'required',
      'date'    =>  'required'
    ]);

    $remark = InquiryRemark::where('id', '=', $remark)->first();
    $remark->update($request->all());

    return response()->json([
      'data'  =>  $remark->toArray()
    ], 200);
  }
}
