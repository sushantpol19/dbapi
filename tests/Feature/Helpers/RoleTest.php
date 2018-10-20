<?php

namespace Tests\Feature\Helpers;

use App\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleTest extends TestCase
{
  use DatabaseTransactions; 

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/roles')
      ->assertStatus(401); 
  }

  /** @test */
  function it_requires_role_and_companyId_in_the_header()
  {
    $this->json('post', '/api/roles', [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function roles_fetched_successfully()
  {
    factory(Role::class)->create([
      'company_id'  =>  $this->company->id,
      'role'        =>  'Admin'
    ]);

    $this->json('get', '/api/roles', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'role'  =>  'Admin'
          ]
        ]
      ]);
  }

  /** @test */
  function role_saved_successfully()
  {
    $payload = [
      'role'  =>  'Admin'
    ];

    $this->json('post', '/api/roles', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJsonStructure([
        'data'  =>  [
          'id', 'role', 'updated_at'
        ]
      ]); 
  }

  /** @test */
  function single_role_fetched_successfully()
  {
    $role = factory(Role::class)->create([
      'company_id'  =>  $this->company->id,
      'role'        =>  'Admin'
    ]);

    $this->json('get', "/api/roles/$role->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'role'  =>  'Admin'
        ]
      ]);
  }

  /** @test */
  function it_requires_role_while_updating()
  {
    $role = factory(Role::class)->create([
      'company_id'  =>  $this->company->id,
      'role'        =>  'Admin'
    ]); 

    $this->json('patch', "/api/roles/$role->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function role_updated_successfullt()
  {
    $role = factory(Role::class)->create([
      'company_id'  =>  $this->company->id 
    ]);
    $role->role = "User";

    $this->json('patch', "/api/roles/$role->id", $role->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'role'  =>  'User'
        ]
      ]);
  }
}
