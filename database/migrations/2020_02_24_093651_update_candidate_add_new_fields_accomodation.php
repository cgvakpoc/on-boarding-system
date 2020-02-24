<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCandidateAddNewFieldsAccomodation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('candidates', 'lead_id')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->dropForeign('lead_id');
                $table->renameColumn('lead_id', 'requirement_lead_id');
                $table->foreign('requirement_lead_id')
                        ->references('id')
                        ->on('leads');
                    });
        }
        Schema::table('candidates', function (Blueprint $table) {
            $table->boolean('candidate_accomodation')->default(0);
            $table->boolean('assigned_consultant_work');
            $table->integer('consultant_lead_id');
            $table->foreign('consultant_lead_id')
                    ->references('id')
                    ->on('leads');
            $table->integer('technical_lead_id');
            $table->foreign('technical_lead_id')
                    ->references('id')
                    ->on('leads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            //
        });
    }
}
