<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_tasks',function(Blueprint $table){
            $table->increments('id');
            $table->integer('candidate_id');
            $table->string('task_details');
            $table->integer('lead_id');
            $table->string('document_path');
            $table->integer('task_status');
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
        Schema::DropIfExists('candidate_tasks');
    }
}
