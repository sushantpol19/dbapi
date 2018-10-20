<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
	use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

		factory(\App\Role::class)->create([
      'company_id' => $this->company->id,
			'role'	=>	'Admin',
		]);

		factory(\App\Role::class)->create([
      'company_id' => $this->company->id,
			'role'	=>	'User',
		]);
	}

	/** @test */
	function it_requires_name_email_and_password()
	{
		$this->json('POST', 'api/register')
			->assertStatus(422);
	}

	/** @test */
	function it_required_password_confirmation()
	{
		$payload = [
			'name'			=>	'test',
			'email'			=>	'test@email.com',
			'password'	=>	'123456'
		];

		$this->json('POST', 'api/register', $payload)
			->assertStatus(422);
	}

	/** @test */
	function user_is_registered_successfully()
	{
		$payload = [
			'name'									=>	'test',
			'email'									=>	'test1@email.com',
			'password'							=>	'123456',
			'password_confirmation'	=>	'123456'
		];

		$this->json('POST', 'api/register', $payload)
			->assertStatus(200)
			->assertJsonStructure([
				'data'	=> [
				 'name',
				 'email',
				 'api_token'
				]
			]);
	}

	/** @test */
	function user_is_assigned_a_role()
	{
		$user = factory(\App\User::class)->create([
			'email'			=>	'email@gmail.com',
			'password'	=>	bcrypt('123456')
		]);
		$token = $user->generateToken();

		$header = [
			'Authorization'	=>	"Bearer $token"
		];

		$user->assignRole('Admin');

		$user->hasRole('Admin');

		$this->assertEquals(true, $user->hasRole('Admin'));
	}
}
