<?php

namespace Tests\Feature\Companies;

use Tests\TestCase;
use App\Models\Companies\Company;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
	use DatabaseTransactions;
	
	protected $user, $headers;

	public function setUp()
	{
		parent::setUp();

		$this->user = factory(\App\User::class)->create([
			'name'	=>	'Vijay',
			'email'	=>	'email@gmail.com',
			'password'	=>	bcrypt('123456')
		]);
		$token = $this->user->generateToken();

		$this->headers = [
			'Authorization'	=>	"Bearer $token"
		];

	}

	/** @test */
	function user_must_be_logged_in()
	{
		$this->json('POST', 'api/companies')
			->assertStatus(401);
	}

	/** @test */	
	function company_is_created_successfully()
	{
		$payload = [
			'name'	=>	'AAIBUZZ'
		];

		$this->json('POST', 'api/companies', $payload, $this->headers)
			->assertStatus(201)
			->assertJsonStructure([
				'data' => [
					'name'
				]
			])
			->assertJson([
				'data'	=>	[
					'name'	=>	'AAIBUZZ'
				]
			]);
	}

	/** @test */
	function company_is_updated_successfully()
	{ 
		$company = $this->createCompany('AAIBUZZ');

		$payload = [
			'name'	=>	'Crane Plus'
		];

		$this->json('PUT', "api/companies/$company->id", $payload, $this->headers)
			->assertStatus(200)
			->assertJson([
				'data'	=>	[ 
					'name'	=>	'Crane Plus' 
				]
			]);
	}

	/** @test */
	function companies_are_listed_correctly_and_against_the_authorized_user()
	{ 
		$this->createCompany();
		$this->createCompany();

		$this->json('GET', 'api/companies', [], $this->headers)
			->assertJsonStructure([
				'data'
			])
			->assertStatus(200);

		$this->assertCount(2, $this->user->companies);
	}

  /** @test */
  function single_company_fetched_successfully()
  {
    $company = $this->createCompany('AAIBUZZ');

    $this->json('get', "/api/companies/$company->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'AAIBUZZ'
        ]
      ]);
  }

	/**
	 * A function to create a company
	 *
	 * @ 
	 */
	function createCompany($name = 'Vijay')
	{
		$company  = factory(Company::class)->create([
			'name'	=>	$name
		]);

		$this->user->hasCompany($company);

		return $company;
	}
}
