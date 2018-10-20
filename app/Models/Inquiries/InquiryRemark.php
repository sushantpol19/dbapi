<?php

namespace App\Models\Inquiries;

use Illuminate\Database\Eloquent\Model;

/*Models*/
use App\Models\Inquiries\Inquiry;

class InquiryRemark extends Model
{
  protected $fillable = [
  	'date', 'remark'
  ];

  /**
   * A remark belongs to inquiry
   *
   * @ 
   */
  public function inquiry()
  {
  	return $this->belongsTo(Inquiry::class);
  }
}
