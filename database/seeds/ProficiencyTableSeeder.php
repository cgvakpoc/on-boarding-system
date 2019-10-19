<?php

use Illuminate\Database\Seeder;

class ProficiencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('proficiency_rating')->insert([
	       [
	       		'rating_name' => 'Fair'
	       ],
	       [
	       		'rating_name' =>	'Good'
	       ],
	       [
	       		'rating_name' =>	'V.Good'
	       ],
	       [
	       		'rating_name' =>	'Excellent'
	       ],
   		]);
       DB::table('passport_status')->insert([
	       [
	       		'status_name' => 'Yes'
	       ],
	       [
	       		'status_name' => 'No'
	       ]
   		]);
    }
}
