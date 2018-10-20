<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use App\Models\Helpers\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryTest extends TestCase
{
	use DatabaseTransactions;

	protected $user, $headers; 

	/** @test */
	function user_is_logged_in()
	{
		$this->json('POST', 'api/categories')
			->assertStatus(401);
	}

	/** @test */
	function it_required_category_parameter()
	{
		$this->json('POST', 'api/categories', [], $this->headers)
			->assertStatus(422);
	}

  /** @test */
  function get_all_the_categories()
  {
    factory(Category::class)->create([
      'company_id'  =>  $this->company->id,
      'category'    =>  'Client'
    ]);

    $this->json('get', '/api/categories', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'category'  =>  'Client'
          ]
        ]
      ]); 
  }

	/** @test */
	function add_a_new_category()
	{
		$payload = [
			'category'	=>	'client'
		];

		$this->json('POST', 'api/categories', $payload, $this->headers)
			->assertStatus(201)
			->assertJson([
				'data'	=>	[
					'category'	=>	'client'
				]
			]);	
	}

  /** @test */
  function single_category_fetched_successfully()
  {
    $category = factory(Category::class)->create([
      'company_id'  =>  $this->company->id,
      'category'    =>  'Client'
    ]);

    $this->json('get', "/api/categories/$category->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'category'  =>  'Client'
        ]
      ]);
  }

  /** @test */
  function category_updated_successfully()
  {
    $category = factory(Category::class)->create([
      'company_id'  =>  $this->company->id,
      'category'    =>  'Client'
    ]);
    $category->category = "Supplier";

    $this->json('patch', "/api/categories/$category->id", $category->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'category'  =>  'Supplier'
        ]
      ]); 
  }
}
