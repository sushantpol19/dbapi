<?php

namespace App\Models\Inquiries;

use Illuminate\Database\Eloquent\Model;

/*Models*/
use App\Models\Inquiries\Inquiry;

class HiringDetail extends Model
{
  protected $fillable = [
  	'nature_of_work', 'from', 'to', 'site_location'
  ];

  /**
   * A hiring detail belongs to inquiry
   *
   * @ 
   */
  public function inquiry()
  {
  	return $this->belongsTo(Inquiry::class);
  }
}
