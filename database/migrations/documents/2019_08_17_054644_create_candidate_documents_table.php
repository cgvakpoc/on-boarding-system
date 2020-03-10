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
            $table->integer('candidate_id');
			$table->enum('document_type', [1, 2])->comment('1 => Joinee Documents, 2=> Technical Document');
            $table->integer('document_id');
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
