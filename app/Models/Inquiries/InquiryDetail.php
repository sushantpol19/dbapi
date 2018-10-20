<?php

namespace App\Models\Inquiries;

use Illuminate\Database\Eloquent\Model;

/*Models*/
use App\Models\Inquiries\Inquiry;

class InquiryDetail extends Model
{
  protected $fillable = [
  	'type', 'make', 'model', 'capacity', 'year_upto', 'budget', 'preferred_location', 'details'
  ];

  /**
   * An inquiry details belongs to an inquiry
   *
   * @ 
   */
  public function inquiry()
  {
  	return $this->belongsTo(Inquiry::class);
  }
}
