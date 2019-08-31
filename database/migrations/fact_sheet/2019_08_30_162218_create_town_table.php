<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('towns',function(Blueprint $table){
            $table->increments('id');
            $table->string('town');
        });

        $towns = ['Agartala','Agra','Ahmedabad','Aizwal','Ajmer','Allahabad','Alleppey','Alibaug','Almora'];

        foreach ($towns as $town) {
            DB::table('towns')->insert(['town' => $town]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('towns');
    }
}
