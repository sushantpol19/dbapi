<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;
use App\Models\Helpers\Type;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TypeTest extends TestCase
{
  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/types')
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_type()
  {
    $this->json('post', '/api/types', [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function types_fetched_successfully()
  {
    $this->disableEH();
    factory(Type::class)->create([
      'company_id' =>  $this->company->id,
      'type'  =>  'hire'
    ]);

    $this->json('get', '/api/types', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'type'  =>  'hire'
          ]
        ]
      ]); 
  }

  /** @test */
  function type_saved_successfully()
  {
    $payload = [
      'type'  =>  'hire'
    ];

    $this->json('post', '/api/types', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'type'  =>  'hire'
        ]
      ]); 
  }

  /** @test */
  function single_type_fetched_successfully()
  {
    $type = factory(Type::class)->create([
      'company_id' =>  $this->company->id,
      'type'  =>  'hire'
    ]);

    $this->json('get', "/api/types/$type->id", $type->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'type'  =>  'hire'
        ]
      ]);
  }

  /** @test */
  function it_requires_type_while_updating()
  {
    $type = factory(Type::class)->create([
      'company_id' =>  $this->company->id,
      'type'  =>  'hire'
    ]);

    $this->json('patch', "/api/types/$type->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function type_updated_successfully()
  {
    $type = factory(Type::class)->create([
      'company_id' =>  $this->company->id,
      'type'  =>  'hire'
    ]);
    $type->type= "sale";

    $this->json('patch', "/api/types/$type->id", $type->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'type'  =>  'sale'
        ]
      ]); 
  }
}
