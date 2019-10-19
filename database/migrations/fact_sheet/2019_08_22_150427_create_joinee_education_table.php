<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoineeEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joinee_education',function(Blueprint $table){
            $table->integer('joinee_id')->unsigned()->index();
            $table->integer('from');
            $table->integer('to');
            $table->string('qualification');
            $table->string('course_name');
            $table->string('institution_name');
            $table->string('medium');
            $table->integer('percentage');
            $table->integer('arrears')->nullable();
            $table->string('class_obtained');
            $table->foreign('joinee_id')
                  ->references('id')
                  ->on('fact_sheet')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('joinee_education');
    }
}
