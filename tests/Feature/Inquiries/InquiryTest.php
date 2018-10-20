<?php

namespace Tests\Feature\Inquiries;

use Tests\TestCase;
use App\Models\Helpers\Mode;
use App\Models\Helpers\Type;
use App\Models\Contacts\Contact;
use App\Models\Inquiries\Inquiry;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InquiryTest extends TestCase
{
	use DatabaseTransactions; 

  protected $type, $type1, $mode, $inquiry, $contact;

	public function setUp()
	{
		parent::setUp(); 

    $this->contact = factory(Contact::class)->create([
      'company_id'  =>  $this->company->id,
      'name'  =>  'Vijay',
      'contact_company_name'  =>  'aaibuzz'
    ]);   

		$this->type = factory(Type::class)->create([
			'company_id'	=>	$this->company->id,
			'type'	=>	'hire'
		]);

    $this->type1 = factory(Type::class)->create([
      'company_id'  =>  $this->company->id,
      'type'  =>  'sale'
    ]);

		$this->mode = factory(Mode::class)->create([
      'company_id'  =>  $this->company->id,
			'mode'	=>	'whatsapp'
		]);

    $this->mode1 = factory(Mode::class)->create([
      'company_id'  =>  $this->company->id,
      'mode'  =>  'email'
    ]);

    $this->inquiry = factory(Inquiry::class)->create([
      'company_id'  =>  $this->company->id,
      'contact_id'  =>  $this->contact->id
    ]);
    $this->inquiry->addDetails([
      'type'  =>  'All Terrain Crane',
      'capacity'  =>  '50'
    ]);
    $this->inquiry->assignType([
      'inquiryTypeId' =>  $this->type->id
    ]);
    $this->inquiry->assignMode([
      'inquiryModeId' =>  $this->mode->id
    ]);
    $this->inquiry->assignMode([
      'inquiryModeId' =>  $this->mode1->id
    ]);
    $this->inquiry->addHiringDetails([
      'nature_of_work'  =>  'erection',
      'to'              =>  'gujarat'
    ]);
	}

	/** @test */
	function user_must_be_logged_in()
	{
		$this->json('POST', 'api/inquiries')
			->assertStatus(401);
	}

	/** @test */
	function it_requires_contactid_date_type_capacity_inquiryTypeIds_inquiryModeIds()
	{
		$this->json('POST', 'api/inquiries', [], $this->headers)
			->assertStatus(422);
	}

  /** @test */
  function inquiries_fetched_successfully()
  { 
    $this->json('get', '/api/inquiries', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'company_id'  =>  $this->company->id,
            'contact_id'  =>  $this->contact->id,
            'date'        =>  '04-05-1992',
            'inquiry_details' =>  [
              0 =>  [
                'type'        =>  'All Terrain Crane',
                'capacity'    =>  '50'
              ]
            ],
            'types' =>  [
              0 =>  [
                'type' =>  $this->type->type
              ]
            ],
            'modes' =>  [
              0 =>  [
                'mode'  =>  $this->mode->mode
              ]
            ],
            'hiring_details'  =>  [
              0 =>  [
                'nature_of_work'  =>  'erection',
                'to'              =>  'gujarat'
              ]
            ]
          ]
        ]
      ]);
  }

	/** @test */
	function inquiry_is_saved_successfully_with_inquiryDetails_inquiryType_inquiryMode_and_hiringDetails()
	{
    $this->disableEH();
		$payload = [ 
			'contact_id'	   =>	'1',
      'cp_id'          => '1',
			'date'				   =>	'04-05-1992',
			'inquiryDetails'  =>  [
        'type'           => 'All Terrain Crane',
        'capacity'       => '50',
      ], 
			'inquiryTypeIds' =>	[
        $this->type->id
      ],
			'inquiryModeIds' =>	[
        $this->mode->id
      ],
			'hiringDetails' =>  [
        'nature_of_work' => 'erection',
        'to'             => 'gujarat'
      ]
		]; 

		$this->json('POST', 'api/inquiries', $payload, $this->headers)
			->assertStatus(201)
			->assertJson([
				'data'	=>	[
					'company_id'	=>	$this->company->id,
					'contact_id'	=>	'1',
          'cp_id'      =>  '1',
					'date'				=>	'04-05-1992',
          'inquiry_details' =>  [
            0 =>  [
              'type'        =>  'All Terrain Crane',
              'capacity'    =>  '50'
            ]
          ],
          'types' =>  [
            0 =>  [
              'type' =>  $this->type->type
            ]
          ],
          'modes' =>  [
            0 =>  [
              'mode'  =>  $this->mode->mode
            ]
          ],
          'hiring_details'  =>  [
            0 =>  [
              'nature_of_work'  =>  'erection',
              'to'              =>  'gujarat'
            ]
          ]
				]
			])
			->assertJsonStructure([
				'data'	=>	[
					'created_at'
				]
			]);
	}

  /** @test */
  function single_inquiry_fetched_successfully()
  {
    $this->json('get', "/api/inquiries/". $this->inquiry->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'company_id'  =>  $this->company->id,
          'contact_id'  =>  '1',
          'date'        =>  '04-05-1992',
          'inquiry_details' =>  [
            0 =>  [
              'type'        =>  'All Terrain Crane',
              'capacity'    =>  '50'
            ]
          ],
          'types' =>  [
            0 =>  [
              'type' =>  $this->type->type
            ]
          ],
          'modes' =>  [
            0 =>  [
              'mode'  =>  $this->mode->mode
            ]
          ],
          'hiring_details'  =>  [
            0 =>  [
              'nature_of_work'  =>  'erection',
              'to'              =>  'gujarat'
            ]
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_contactid_date_type_capacity_inquiryTypeIds_inquiryModeIds_while_updating()
  {
    $this->json('patch', "/api/inquiries/" . $this->inquiry->id, [], $this->headers)
      ->assertStatus(422); 
  }

  /** @test */
  function inquiry_updated_successfully()
  {
    $this->disableEH();

    $inquiry = [ 
      'id'            =>  $this->inquiry->id,
      'contact_id'     => '2',
      'date'           => '04-05-1993',
      'inquiryDetails'  =>  [
        'id'  =>  $this->company->inquiries()->find($this->inquiry->id)->inquiry_details[0]->id,
        'type'           => 'All Terrain Crane',
        'capacity'       => '50',
      ],  
      'inquiryTypeIds' => [
        $this->type->id, $this->type1->id
      ],
      'inquiryModeIds' => [
        $this->mode->id
      ],
      'hiringDetails' =>  [
        'id'  =>   $this->company->inquiries()->find($this->inquiry->id)->hiring_details[0]->id,
        'nature_of_work' => 'erectio',
        'to'             => 'gujarat'
      ]
    ];

    $this->json('patch', '/api/inquiries/'. $inquiry['id'], $inquiry, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'company_id'  =>  $this->company->id,
          'contact_id'  =>  '2',
          'date'        =>  '04-05-1993',
          'inquiry_details' =>  [
            0 =>  [
              'type'        =>  'All Terrain Crane',
              'capacity'    =>  '50'
            ]
          ],
          'types' =>  [
            0 =>  [
              'type' =>  $this->type->type
            ],
            1 =>  [
              'type' =>  $this->type1->type
            ]
          ],
          'modes' =>  [
            0 =>  [
              'mode'  =>  $this->mode->mode
            ]
          ],
          'hiring_details'  =>  [
            0 =>  [
              'nature_of_work'  =>  'erectio',
              'to'              =>  'gujarat'
            ]
          ]
        ]
      ]);
  } 
  
}
