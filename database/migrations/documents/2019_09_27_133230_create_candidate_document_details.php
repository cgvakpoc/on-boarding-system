<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateDocumentDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_document_details',function(Blueprint $table){
            $table->increments('id');
            $table->integer('candidate_document_id');
            $table->string('file_name',255);
            $table->string('path',255);
            $table->foreign('candidate_document_id')
                  ->references('id')
                  ->on('candidate_documents')
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
        Schema::dropIfExists('document_details');
    }
}
