<?php

namespace Tests\Feature\Inquiries;

use Tests\TestCase;
use App\Models\Inquiries\Inquiry;
use App\Models\Inquiries\InquiryRemark;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InquiryRemarkTest extends TestCase
{
  protected $inquiry;

  public function setUp()
  {
    parent::SetUp();

    $this->inquiry = factory(Inquiry::class)->create([
      'company_id'  =>  $this->company->id,
      'contact_id'  =>  '2',
      'date'        =>  '04-05-1992'
    ]);

  }

  /** @test */
  function remarks_fetched_successfully()
  {
    factory(InquiryRemark::class)->create([
      'inquiry_id'  =>  $this->inquiry->id,
      'remark'      =>  'Inquiry Closed',
      'date'        =>  '04-05-1992'
    ]);

    $this->json('get', "api/inquiries/" . $this->inquiry->id . "/remarks", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'remark'  =>  'Inquiry Closed',
            'date'    =>  '04-05-1992'
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_remark_and_date()
  {
    $this->json('post', "api/inquiries/" . $this->inquiry->id . "/remarks", [], $this->headers)
      ->assertStatus(422);
  }
  /** @test */
  function add_remarks_against_an_inquiry()
  {
    $payload = [
      'date'    =>  '04-05-1992',
      'remark'  =>  'Done'
    ];  

    $this->json('POST', "api/inquiries/" .$this->inquiry->id."/remarks", $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'date'  =>  '04-05-1992',
          'remark'  =>  'Done'
        ]
      ])
      ->assertJsonStructure([
        'data'  =>  [
          'created_at'
        ]
      ]);
  } 

  /** @test */
  function single_inquiry_fetched_successfully()
  {
    $remark = factory(InquiryRemark::class)->create([
      'inquiry_id'  =>  $this->inquiry->id,
      'remark'      =>  'Inquiry Closed',
      'date'        =>  '04-05-1992'
    ]);

    $remark2 = factory(InquiryRemark::class)->create([
      'inquiry_id'  =>  $this->inquiry->id,
      'remark'      =>  'Inquiry Closed',
      'date'        =>  '04-05-1992'
    ]);

    $this->json('get', "/api/inquiries/".$this->inquiry->id."/remarks/".$remark2->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'remark'      =>  'Inquiry Closed',
          'date'        =>  '04-05-1992'
        ]
      ]);
  }

  /** @test */
  function it_requires_remark_and_date_while_updating()
  {
    $remark = factory(InquiryRemark::class)->create([
      'inquiry_id'  =>  $this->inquiry->id,
      'remark'      =>  'Inquiry Closed',
      'date'        =>  '04-05-1992'
    ]);

    $this->json('patch', "/api/inquiries/".$this->inquiry->id."/remarks/".$remark->id, [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function remark_updated_successfully()
  {
    $remark = factory(InquiryRemark::class)->create([
      'inquiry_id'  =>  $this->inquiry->id,
      'remark'      =>  'Inquiry Closed',
      'date'        =>  '04-05-1992'
    ]);
    $remark->remark = "Inquiry Open";

    $this->json('patch', "/api/inquiries/".$this->inquiry->id."/remarks/".$remark->id, $remark->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'remark'      =>  'Inquiry Open',
          'date'        =>  '04-05-1992'
        ]
      ]);
  }
}
