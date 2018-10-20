<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use App\Models\Contacts\Contact;
use App\Models\Helpers\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactTest extends TestCase
{
	use DatabaseTransactions;

	protected $category, $category2;

	public function setUp()
	{
		parent::setUp(); 

		$this->category = factory(Category::class)->create([
			'company_id'		=>	$this->company->id,
			'category'	=>	'client'
		]);

    $this->category2 = factory(Category::class)->create([
      'company_id'    =>  $this->company->id,
      'category'  =>  'supplier'
    ]);
	}

	/** @test */
	function it_requires_authorized_user()
	{
		$this->json('POST', 'api/contacts')
			->assertStatus(401);	
	}

	/** @test */
	function it_requires_name_email_phone_category_and_company_name()
	{
		$this->json('POST', 'api/contacts', [], $this->headers)
			->assertStatus(422);
	}

  /** @test */
  function contacts_fetched_successfully()
  {
    $this->disableEH();
    $contact = factory(Contact::class)->create([
      'company_id'  =>  $this->company->id,
      'name'  =>  'Vijay',
      'contact_company_name'  =>  'aaibuzz'
    ]); 
    $contact->assignCategory($this->category->id);

    $this->json('get', '/api/contacts', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'name'  =>  'Vijay',
            'contact_company_name'  =>  'aaibuzz'
          ]
        ]
      ]);
  }

	/** @test */
	function contact_created_successfully()
	{
    $this->disableEH();

		$payload = [
			'name'	=>	'Vijay',
			'contact_company_name'	=>	'aaibuzz',
			'email'	=>	'email@email.com',
			'phone'	=>	'9579862371',
			'category_ids'	=>	[
        $this->category->id
      ]
		];

		$this->json('POST', 'api/contacts', $payload, $this->headers)
			->assertStatus(201)
			->assertJson([
				'data'	=>	[
					'name'	=>	'Vijay', 
					'contact_company_name'	=>	'aaibuzz'
				]
			]);	
	}

	/** @test */
	function a_contact_belongs_to_category()
	{
		$contact = factory(Contact::class)->create([
			'company_id'	=>	$this->company->id,
			'name'	=>	'Vijay',
			'contact_company_name'	=>	'aaibuzz'
		]);  	

		$contact->assignCategory($this->category->id);

		$this->assertEquals(true, $contact->hasCategory($this->category->category));
	}

  /** @test */
  function single_contact_fetched_successfully()
  {
    $contact = factory(Contact::class)->create([
      'company_id'  =>  $this->company->id,
      'name'  =>  'Vijay',
      'contact_company_name'  =>  'aaibuzz'
    ]); 

    $this->json('get', "/api/contacts/$contact->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Vijay',
          'contact_company_name'  =>  'aaibuzz'
        ]
      ]);
  }

  /** @test */
  function contact_updated_successfully()
  {
    $this->disableEH();
    $contact = factory(Contact::class)->create([
      'company_id'  =>  $this->company->id,
      'name'  =>  'Vijay',
      'contact_company_name'  =>  'aaibuzz'
    ]); 
    $contact->assignCategory($this->category->id);
    $contact->name = 'Ajay';
    $contact->category_ids = [
      $this->category->id, $this->category2->id 
    ];

    $this->assertCount(1, $contact->categories);

    $this->json('patch', "/api/contacts/$contact->id", $contact->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Ajay',
          'contact_company_name'  =>  'aaibuzz'
        ]
      ]);

    $this->assertEquals(true, $contact->hasCategory($this->category->category));
    $contact->refresh();
    $this->assertCount(2, $contact->categories);
  }

}
