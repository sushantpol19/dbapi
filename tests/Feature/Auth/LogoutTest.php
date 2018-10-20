<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogoutTest extends TestCase
{
	use DatabaseTransactions;

	/** @test */
	function it_requires_user_must_be_logged_in()
	{
		$this->json('POST', 'api/logout')
			->assertStatus(204);
	}

	/** @test */
	function user_logged_out_successfully()
	{
		$user = factory(\App\User::class)->create([
			'email'			=>	'email@email.com',
			'password'	=>	'123456'
		]);
		$token = $user->generateToken();

		$header = [
			'Authorization'	=>	"Bearer $token"
		];

		$this->json('POST', 'api/logout', [], $header)
			->assertStatus(200);
	}
}
