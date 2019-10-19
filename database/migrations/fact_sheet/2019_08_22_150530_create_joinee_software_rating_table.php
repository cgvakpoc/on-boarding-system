<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoineeSoftwareRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joinee_software_rating',function(Blueprint $table){
            $table->integer('joinee_id')->unsigned()->index();
            $table->string('software_subject');
            $table->integer('software_rating');
            $table->foreign('joinee_id')
                  ->references('id')
                  ->on('fact_sheet')
                  ->onDelete('cascade');
        });

        Schema::create('proficiency_rating',function(Blueprint $table){
            $table->increments('id');
            $table->string('rating_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('joinee_software_rating');
        Schema::dropIfExists('proficiency_rating');
    }
}
