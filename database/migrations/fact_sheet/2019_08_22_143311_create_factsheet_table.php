<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactsheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_sheet',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->integer('pos_applied');
            $table->string('email')->unique();
            $table->string('phonenumber')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('age');
            $table->date('dob');
            $table->string('address');
            $table->integer('town');
            $table->integer('state');
            $table->string('father_name');
            $table->string('father_occupation');
            $table->integer('marital_status');
            $table->string('spouse_name')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('religion');
            $table->integer('edit_state');
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
        Schema::dropIfExists('fact_sheet');
    }
}
