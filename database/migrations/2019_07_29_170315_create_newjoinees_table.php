<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewjoineesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_joinees',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->integer('department_id');
            $table->integer('designation_id');
            $table->date('date_of_birth');
            $table->date('date_of_join');
            $table->string('father_name');
            $table->string('email')->unique();
            $table->string('cold_calling_status');
            $table->string('commitment_status');
            $table->string('joining_bonus')->nullable();
            $table->string('recruiter_name');
            $table->string('requirement_details');
            $table->string('source_of_hiring');
            $table->string('location');
            $table->string('travel_accomodation');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('new_joinees');
    }
}
