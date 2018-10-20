<?php

namespace Tests;

use Exception;
use App\Exceptions\Handler;
use App\Models\Companies\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

trait CreatesApplication
{

  use DatabaseTransactions;

  protected $user, $headers, $company;

  /**
   * Creates the application.
   *
   * @return \Illuminate\Foundation\Application
   */
  public function createApplication()
  {
    $app = require __DIR__.'/../bootstrap/app.php';

    $app->make(Kernel::class)->bootstrap();

    Hash::setRounds(4);

    $this->user = factory(\App\User::class)->create();
    $token = $this->user->generateToken(); 

    $this->company  = factory(Company::class)->create([
      'name'  =>  'Aaibuzz'
    ]);

    $this->headers = [
      'Authorization' =>  "Bearer $token",
      'company_id'    =>  $this->company->id
    ];

    return $app;
  }

  public function disableEH()
  {
  	app()->instance(Handler::class, new class extends Handler {
  		public function __construct(){}
  		public function report(Exception $exception){}
  		public function render($request, Exception $exception)
	    { 
	      throw $exception;
	    }
  	});
  }
}
