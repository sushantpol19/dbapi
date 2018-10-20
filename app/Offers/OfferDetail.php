<?php

namespace App\Offers;

use App\Offers\Offer;
use Illuminate\Database\Eloquent\Model;

class OfferDetail extends Model
{
  protected $fillable = [
    'offer_id', 'type', 'make', 'model', 'capacity', 'year', 'preferred_location', 'details'
  ];  

  /*
   * Offer details belongs to offer
   *
   *@
   */
  public function offer()
  {
    return $this->belongsTo(Offer::class);
  }
}
