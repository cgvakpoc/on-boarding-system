<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->delete();

		DB::table('users')->insert([
			'name' => 'Baskar',
			'email' => 'baskar@cgvakindia.com',
			'password' => bcrypt('!@qwASzx'),
		]);
	}
}
