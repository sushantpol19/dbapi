<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('offer_id');
            $table->string('type');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('capacity');
            $table->string('year')->nullable();
            $table->string('budget')->nullable();
            $table->string('preferred_location')->nullable();
            $table->string('details')->nullable(); 
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
        Schema::dropIfExists('offer_details');
    }
}
