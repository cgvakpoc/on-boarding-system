<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_documents',function(Blueprint $table){
            $table->increments('id');
            $table->integer('candidate_id')->unsigned();
            $table->string('document_title');
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
        Schema::DropIfExists('candidate_documents');
    }
}
