<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadidateResumeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_resume', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candidate_id');
            $table->text('resume_path');
            $table->foreign('candidate_id')
                    ->references('id')
                    ->on('candidates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_resume', function (Blueprint $table) {
            //
        });
    }
}
