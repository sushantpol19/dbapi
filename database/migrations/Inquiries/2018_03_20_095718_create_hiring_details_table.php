<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHiringDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiring_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inquiry_id');
            $table->string('nature_of_work')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('site_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hiring_details');
    }
}
