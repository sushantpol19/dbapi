<?php

namespace App\Offers;

use Illuminate\Database\Eloquent\Model;

class OfferRemark extends Model
{
  protected $fillable = [
    'date', 'remark'
  ]; 

  /*
   * An offer remark belongs to offer
   *
   *@
   */
  public function offer()
  {
    return $this->belongsTo(Offer::class);
  }
}
