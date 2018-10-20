<?php

namespace Tests\Feature\Offers;

use Tests\TestCase;
use App\Offers\Offer;
use App\Helpers\Status;
use App\Models\Helpers\Mode;
use App\Models\Inquiries\Inquiry;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OfferTest extends TestCase
{
  use DatabaseTransactions; 

  protected $inquiry, $offer, $mode, $status;

  public function setUp()
  {
    parent::setUp();

    $this->inquiry = factory(Inquiry::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $this->offer = factory(Offer::class)->create([
      'inquiry_id'  =>  $this->inquiry->id,
      'date'        =>  '04-05-1992'
    ]);

    $this->mode = factory(Mode::class)->create([
      'company_id'  =>  $this->company->id,
      'mode'  =>  'email'
    ]);

    $this->mode1 = factory(Mode::class)->create([
      'company_id'  =>  $this->company->id,
      'mode'  =>  'email'
    ]);

    $this->status = factory(Status::class)->create([
      'company_id'  =>  $this->company->id,
      'status'  =>  'completed'
    ]);

    $this->status1 = factory(Status::class)->create([
      'company_id'  =>  $this->company->id,
      'status'  =>  'completed'
    ]);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', "/api/inquiries/".$this->inquiry->id."/offers")
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_date()
  {
    $this->json('post', "/api/inquiries/".$this->inquiry->id."/offers", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function offers_fetched_successfully()
  {
    $this->json('get', "/api/inquiries/".$this->inquiry->id."/offers", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'date'  =>  $this->offer->date
          ]
        ]
      ]);
  }

  /** @test */
  // function offer_saved_successfully()
  // {
  //   $payload = [
  //     'date'  =>  '04-05-1992'
  //   ];

  //   $this->json('post', "/api/inquiries/".$this->inquiry->id."/offers", $payload, $this->headers)
  //     ->assertStatus(201)
  //     ->assertJson([
  //       'data'  =>  [
  //         'date'  =>  '04-05-1992'
  //       ]
  //     ]);
  // }

  /** @test */
  function offer_details_added_successfully()
  {
    $payload = [
      'type'  =>  'Telescopic Crane', 
      'capacity'  =>  '50' 
    ];
    $this->offer->addDetails($payload);

    $this->assertCount(1, $this->offer->offer_details);
  }

  /** @test */
  function offer_mode_added_successfully()
  {
    $this->offer->assignMode($this->mode->id);

    $this->assertCount(1, $this->offer->modes);
  }

  /** @test */
  function offer_status_added_successfully()
  {
    $this->offer->assignStatus($this->status->id);
    $this->assertCount(1, $this->offer->statuses);
  }

  /** @test */
  function offer_added_successfully_with_details_mode_and_status()
  {
    $this->disableEH();
    $payload = [
      'date'  =>  '04-05-1992',
      'offerDetails'  =>  [
        'type'  =>  'Telescopic Crane',
        'make'  =>  'Demag',
        'model' =>  'AC 265',
        'capacity'  =>  '50',
        'year'  => '2000',
        'preferred_location'  =>  'Mumbai',
        'details' =>  'Crane is good in condition'
      ],
      'offerModeIds' =>  [
        $this->mode->id
      ],
      'offerStatusIds'  =>  [
        $this->status->id
      ]
    ];

    $this->json('post', "/api/inquiries/".$this->inquiry->id."/offers", $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'date'  =>  '04-05-1992',
          'offer_details'  =>  [
            0 =>  [
              'type'  =>  'Telescopic Crane',
              'make'  =>  'Demag',
              'model' =>  'AC 265',
              'capacity'  =>  '50',
              'year'  => '2000',
              'preferred_location'  =>  'Mumbai',
              'details' =>  'Crane is good in condition'
            ] 
          ],
          'modes' =>  [
            0 =>  [
              'id'  => $this->mode->id,
              'mode'  =>  'email'
            ]
          ],
          'statuses'  =>  [
            0 =>  [
              'id'  =>  $this->status->id,
              'status'  =>  'completed'
            ]
          ]
        ]
      ]);
  }

  /** @test */
  function single_offer_fetched_successfully()
  {
    $this->disableEH();
    $this->json('get', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id, $this->offer->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'date'  =>  '04-05-1992'
        ]
      ]);
  }

  /** @test */
  function it_requires_date_while_updating()
  {
    $this->json('patch', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id, [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  // function offer_updated_successfully()
  // {
  //   $this->disableEH();
  //   $this->offer->date = '04-05-1993';
  //   $this->json('patch', "/api/inquiries/".$this->inquiry->id."/offers/".$this->offer->id, $this->offer->toArray(), $this->headers)
  //     ->assertStatus(200)
  //     ->assertJson([
  //       'data'  =>  [
  //         'date'  =>  '04-05-1993'
  //       ]
  //     ]);
  // }

  /** @test */
  function offer_updated_successfully_with_details_mode_and_status()
  {
    $this->disableEH();
    $payload = [
      'date'  =>  '04-05-1993',
      'offerDetails'  =>  [
        'type'  =>  'Telescopic Cranes',
        'make'  =>  'Demag',
        'model' =>  'AC 265',
        'capacity'  =>  '50',
        'year'  => '2000',
        'preferred_location'  =>  'Mumbai',
        'details' =>  'Crane is good in condition'
      ],
      'offerModeIds' =>  [
        $this->mode->id, $this->mode1->id
      ],
      'offerStatusIds'  =>  [
        $this->status->id, $this->status1->id
      ]
    ];

    $this->json('post', "/api/inquiries/".$this->inquiry->id."/offers", $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'date'  =>  '04-05-1993',
          'offer_details'  =>  [
            0 =>  [
              'type'  =>  'Telescopic Cranes',
              'make'  =>  'Demag',
              'model' =>  'AC 265',
              'capacity'  =>  '50',
              'year'  => '2000',
              'preferred_location'  =>  'Mumbai',
              'details' =>  'Crane is good in condition'
            ] 
          ],
          'modes' =>  [
            0 =>  [
              'id'  => $this->mode->id,
              'mode'  =>  'email'
            ],
            1 =>  [
              'id'  => $this->mode1->id,
              'mode'  =>  'email'
            ]
          ],
          'statuses'  =>  [
            0 =>  [
              'id'  =>  $this->status->id,
              'status'  =>  'completed'
            ],
            1 =>  [
              'id'  =>  $this->status1->id,
              'status'  =>  'completed'
            ]
          ]
        ]
      ]);
  }
}
