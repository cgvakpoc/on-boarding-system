<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoineeExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joinee_experience',function(Blueprint $table){
            $table->integer('joinee_id')->unsigned()->index();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->string('total_exp')->nullable();
            $table->string('designation')->nullable();
            $table->string('organisation')->nullable();
            $table->string('location')->nullable();
            $table->string('reason_to_leave')->nullable();
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
        Schema::dropIfExists('joinee_experience');
    }
}
