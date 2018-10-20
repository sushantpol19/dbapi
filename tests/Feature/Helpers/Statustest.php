<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;
use App\Helpers\Status;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Statustest extends TestCase
{
  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/statuses')
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_status()
  {
    $this->json('post', '/api/statuses', [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function statuses_fetched_successfully()
  {
    factory(Status::class)->create([
      'company_id'  =>  $this->company->id,
      'status'  =>  'completed'
    ]);

    $this->json('get', '/api/statuses', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'status'  =>  'completed'
          ]
        ]
      ]);
  }

  /** @test */
  function status_saved_successfully()
  {
    $payload = [
      'status'  =>  'completed'
    ];

    $this->json('post', '/api/statuses', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'status'  =>  'completed'
        ]
      ]); 
  }

  /** @test */
  function single_status_fetched_successfully()
  {
    $status = factory(Status::class)->create([
      'company_id'  =>  $this->company->id,
      'status'  =>  'completed'
    ]);

    $this->json('get', "/api/statuses/$status->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'status'  =>  'completed'
        ]
      ]); 
  }

  /** @test */
  function it_requires_name_while_updating()
  {
    $status = factory(Status::class)->create([
      'company_id'  =>  $this->company->id,
      'status'  =>  'completed'
    ]);

    $this->json('patch', "/api/statuses/$status->id", [], $this->headers)
      ->assertStatus(422); 
  }

  /** @test */
  function status_updated_successfully()
  {
    $status = factory(Status::class)->create([
      'company_id'  =>  $this->company->id,
      'status'  =>  'completed'
    ]);
    $status->status = "incomplete";
    
    $this->json('patch', "/api/statuses/$status->id", $status->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'status'  =>  'incomplete'
        ]
      ]); 
  }
}
