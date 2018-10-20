<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inquiry_id');
            $table->string('type');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('capacity');
            $table->string('year_upto')->nullable();
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
        Schema::dropIfExists('inquiry_details');
    }
}
