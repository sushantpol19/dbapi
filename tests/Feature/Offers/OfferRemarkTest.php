<?php

namespace Tests\Feature\Offers;

use Tests\TestCase;
use App\Offers\Offer;
use App\Offers\OfferRemark;
use App\Models\Inquiries\Inquiry;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OfferRemarkTest extends TestCase
{
  use DatabaseTransactions;

  protected $inquiry, $offer, $remark;  
  public function setUp()
  {
    parent::SetUp();

    $this->inquiry = factory(Inquiry::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $this->offer = factory(Offer::class)->create([
      'inquiry_id'  =>  $this->inquiry->id,
      'date'        =>  '04-05-1992'
    ]);

    $this->remark = factory(OfferRemark::class)->create([
      'offer_id'  =>  $this->offer->id,
      'date'        =>  '04-05-1992',
      'remark'      =>  'hired'
    ]);
  }
  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id."/remarks")
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_date_and_remark()
  {
    $this->json('post', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id."/remarks", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function remarks_fetched_successfully()
  {
    $this->disableEH();
    $this->json('get', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id."/remarks", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'offer_id'  =>  $this->inquiry->id,
            'date'        =>  '04-05-1992',
            'remark'      =>  'hired'
          ]
        ]
      ]);
  }

  /** @test */
  function remark_added_successfully()
  {
    $this->disableEH();

    $payload = [
      'date'  =>  '04-05-1992',
      'remark'  =>  'hired'
    ];

    $this->json('post', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id."/remarks", $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'date'  =>  '04-05-1992',
          'remark'  =>  'hired'
        ]
      ]);
  }

  /** @test */
  function single_offer_fetched_successfully()
  {
    $this->json('get', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id."/remarks/".$this->remark->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'date'  =>  '04-05-1992',
          'remark'  =>  'hired'
        ]
      ]);
  }

  /** @test */
  function it_requires_date_and_remark_while_updating()
  {
    $this->offer->remark = "sold";

    $this->json('patch', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id."/remarks/".$this->remark->id, [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function offer_updated_successfully()
  {
    $this->offer->remark = "sold";

    $this->json('patch', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id."/remarks/".$this->remark->id, $this->offer->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'date'  =>  '04-05-1992',
          'remark'  =>  'sold'
        ]
      ]);
  }
}
