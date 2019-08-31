<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoineeSiblingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joinee_siblings',function(Blueprint $table){
            $table->integer('joinee_id')->unsigned()->index();
            $table->string('sibling_name')->nullable();
            $table->string('course')->nullable();
            $table->string('institution')->nullable();

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
        Schema::dropIfExists('joinee_siblings');
    }
}
