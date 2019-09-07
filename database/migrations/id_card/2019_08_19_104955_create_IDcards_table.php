<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIDcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('id_card',function(Blueprint $table){
            $table->increments('id');
            $table->integer('emp_code');
            $table->integer('candidate_id')->unsigned();
            $table->string('name');
            $table->text('address');
            $table->string('blood_group');
            $table->text('document_path');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->foreign('candidate_id')
                  ->references('id')
                  ->on('candidates')
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
        Schema::DropIfExists('id_card');
    }
}
