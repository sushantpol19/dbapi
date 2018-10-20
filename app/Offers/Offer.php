<?php

namespace App\Offers;

use App\Helpers\Status;
use App\Offers\OfferDetail;
use App\Offers\OfferRemark;
use App\Models\Helpers\Mode;
use App\Models\Inquiries\Inquiry;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
  protected $fillable = [
    'inquiry_id', 'cp_id', 'date'
  ];

  /*
   * An offer belongs to inquiry
   *
   *@
   */
  public function inquiry()
  {
    return belongsTo(Inquiry::class);
  }

  /*
   * A offer has many offer details
   *
   *@
   */
  public function offer_details()
  {
    return $this->hasMany(OfferDetail::class);
  } 

  /*
   * An offer belongs to many modes
   *
   *@
   */
  public function modes()
  {
    return $this->belongsToMany(Mode::class);
  }

  /*
   * An offer belongs to many statuses
   *
   *@
   */
  public function statuses()
  {
    return $this->belongsToMany(Status::class);
  }

  /*
   * Add offer details to an offer
   *
   *@
   */
  public function addDetails($details)
  {
    if(isset($details['id'])){
      $offer_details = OfferDetail::where('id', '=', $details['id'])->first();
      $offer_details->update($details);
    }
    else {
      $offer_details = new OfferDetail($details);
      $this->offer_details()->save($offer_details); 
    }
    $this->refresh(); 

    return $this;
  }

  /*
   * To detach all the modes of an offer
   *
   *@
   */
  public function detachModes()
  {
    foreach($this->modes as $mode) {
      $this->modes()->detach($mode);
    }
  }

  /*
   * To detach all the statuses of an order
   *
   *@
   */
  public function detachStatuses()
  {
    foreach($this->statuses as $status) {
      $this->statuses()->detach($status);
    }
  }

  /*
   * To assign a mode to an offer
   *
   *@
   */
  public function assignMode($modeId)
  {
    $mode = Mode::where('id', '=', $modeId)->first();
    $this->modes()->syncWithoutDetaching($mode);
  }

  /*
   * To assign a status to an offer
   *
   *@
   */
  public function assignStatus($statusId)
  {
    $status = Status::where('id', '=', $statusId)->first();
    $this->statuses()->syncWithoutDetaching($status);
  }

  /*
   * An offer has many offer remarks
   *
   *@
   */
  public function offer_remarks()
  {
    return $this->hasMany(OfferRemark::class);
  }
}
