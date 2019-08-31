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
            $table->integer('user_id');
            $table->string('name');
            $table->string('address');
            $table->string('blood_group');
            $table->string('document_path');
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
        Schema::DropIfExists('id_card');
    }
}
