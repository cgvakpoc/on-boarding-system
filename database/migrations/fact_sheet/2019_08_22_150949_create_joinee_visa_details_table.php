<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoineeVisaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visa_details',function(Blueprint $table){
            $table->integer('joinee_id')->unsigned();
            $table->text('visa_applied')->nullable();
            $table->text('reject_reason')->nullable();
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
        Schema::dropIfExists('visa_details');
    }
}
