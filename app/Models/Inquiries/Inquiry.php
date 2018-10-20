<?php

namespace App\Models\Inquiries;

use App\Offers\Offer;
use App\Models\Helpers\Mode;
use App\Models\Helpers\Type;
use App\Models\Contacts\Contact;
use App\Models\Companies\Company;
use App\Models\Inquiries\HiringDetail;
use App\Models\Inquiries\InquiryDetail;
use App\Models\Inquiries\InquiryRemark;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
  protected $fillable = [
  	'company_id',	'contact_id', 'cp_id', 'date'
  ];

  /**
   * An inquiry belongs to a company
   *
   * @ 
   */
  public function company()
  {
  	return $this->belongsTo(Company::class);
  }

  /**
   * An contact makes an inquiry ie inquiry also belongs to a contact
   *
   * @ 
   */
  public function contact()
  {
  	return $this->belongsTo(Contact::class);
  }

  /**
   * An inquriy can have many inquiry details
   *
   * @ 
   */
  public function inquiry_details()
  {
  	return $this->hasMany(InquiryDetail::class);
  }

  /**
   * An inquiry can have many types like for hire and for sale
   *
   * @ 
   */
  public function types()
  {
  	return $this->belongsToMany(Type::class)
  		->withTimestamps();
  }

  /**
   * An inquiry can be from many modes (whatsapp, mail)
   *
   * @ 
   */
  public function modes()
  {
  	return $this->belongsToMany(Mode::class)
  		->withTimestamps();
  }

  /**
   * A inquiry can have many hiring details
   *
   * @ 
   */
  public function hiring_details()
  {
  	return $this->hasMany(HiringDetail::class);
  }

  /**
   * To store a new inquiry
   *
   * @ 
   */
  public function store()
  {
    // Save Inquiry
    $this->addInquiry(); 

  	// Save Inquiry details 
    $this->addDetails(request()->inquiryDetails);

    $this->detachTypes();
    foreach(request()->inquiryTypeIds as $inquiryTypeId) {
      // Save inquiry type
      $this->assignType($inquiryTypeId); 
    }

    $this->detachModes();
    foreach(request()->inquiryModeIds as $inquiryModeId) {
      // Save inquiry mode
      $this->assignMode($inquiryModeId); 
    } 

    // Save the hiring details
    $this->addHiringDetails(request()->hiringDetails);

  	return $this;
  } 

  /*
   * To save the inquiry
   *
   *@
   */
  public function addInquiry()
  {
    if(isset(request()->id)){
      $inquiry = Inquiry::where('id', '=', request()->id)->first();
      $inquiry->update(request()->all());
    }
    else {
      if(request()->header('company-id')) {
        $company = Company::find(request()->header('company-id'));
        if($company)
          $company ? $company->inquiries()->save($this) : '';
      }  
    }
  }

  /*
   * To add inquiry details
   *
   *@
   */
  public function addDetails($details)
  {
    if(isset($details['id'])){
      $inquiry_details = InquiryDetail::where('id', '=', $details['id'])->first();
      $inquiry_details->update($details);
    }
    else {
      $inquiry_details = new InquiryDetail($details);
      $this->inquiry_details()->save($inquiry_details); 
    }
    $this->refresh();

    return $this; 
  }

  /*
   * To detach inquiry type
   *
   *@
   */
  public function detachTypes()
  {
    foreach($this->types as $type)
    {
      $this->types()->detach($type);
    }
  }

  /*
   * To assign inquiry type
   *
   *@
   */
  public function assignType($typeId)
  {
    // echo $typeId;

    // Save inquiry type
    $type = Type::where('id', '=', $typeId)->first();
    $this->types()->syncWithoutDetaching($type);
    $this->refresh();

    return $this;
  }

  /*
   * To detach all the modes
   *
   *@
   */
  public function detachModes()
  {
    foreach($this->modes as $mode)
    {
      $this->modes()->detach($mode);
    }
  }

  /*
   * To assign inquiry mode
   *
   *@
   */
  public function assignMode($modeId)
  {
    // Save inquiry mode
    $mode = Mode::where('id', '=', $modeId)->first();
    $this->modes()->syncWithoutDetaching($mode);
    $this->refresh();

    return $this;
  }

  /*
   * To add the hiring details
   *
   *@
   */
  public function addHiringDetails($request)
  { 
    if(isset($request['id'])){
      $hiring = HiringDetail::where('id', '=', $request['id'])->first();
      $hiring->update($request);
    }
    else{
      $hiring = new HiringDetail($request);
      $this->hiring_details()->save($hiring); 
    }
    $this->refresh();

    return $this;
  }

  /**
   * An inquiry has many remarks
   *
   * @ 
   */
  public function inquiry_remarks()
  {
  	return $this->hasMany(InquiryRemark::class);
  }

  /*
   * An inquiry has many offers
   *
   *@
   */
  public function offers()
  {
    return $this->hasMany(Offer::class)
      ->with('offer_details', 'modes', 'statuses', 'offer_remarks');
  }
}
