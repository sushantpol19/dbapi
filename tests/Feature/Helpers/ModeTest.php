<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;
use App\Models\Helpers\Mode;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModeTest extends TestCase
{
  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/modes')
      ->assertStatus(401); 
  }

  /** @test */
  function it_requires_mode()
  {
    $this->json('post', '/api/modes', [], $this->headers)
      ->assertStatus(422); 
  }

  /** @test */
  function modes_fetched_successfully()
  {
    factory(Mode::class)->create([
      'company_id' =>  $this->company->id,
      'mode'  =>  'whatsapp'
    ]);

    $this->json('get', '/api/modes', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'mode'  =>  'whatsapp'
          ]
        ]
      ]); 
  }

  /** @test */
  function mode_saved_successfully()
  {
    $this->disableEH();

    $payload = [
      'mode'  =>  'whatsapp'
    ];

    $this->json('post', '/api/modes', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'mode'  =>  'whatsapp'
        ]
      ]); 
  }

  /** @test */
  function single_mode_fetched_successfully()
  {
    $mode = factory(Mode::class)->create([
      'company_id' =>  $this->company->id,
      'mode'  =>  'whatsapp'
    ]);

    $this->json('get', "/api/modes/$mode->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'mode'  =>  'whatsapp'
        ]
      ]);
  }

  /** @test */
  function it_requires_mode_while_updating()
  {
    $mode = factory(Mode::class)->create([
      'company_id' =>  $this->company->id,
      'mode'  =>  'whatsapp'
    ]);
    $mode->mode = 'email';

    $this->json('patch', "/api/modes/$mode->id", [], $this->headers)
      ->assertStatus(422); 
  }

  /** @test */
  function mode_updated_successfully()
  {
    $mode = factory(Mode::class)->create([
      'company_id' =>  $this->company->id,
      'mode'  =>  'whatsapp'
    ]);
    $mode->mode = 'email';

    $this->json('patch', "/api/modes/$mode->id", $mode->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'mode'  =>  'email'
        ]
      ]); 
  }
}
