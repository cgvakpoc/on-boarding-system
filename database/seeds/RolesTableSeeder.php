<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('roles')->delete();

		DB::table('roles')->insert([

			[
				'name' => 'admin',
				'display_name' => 'Administrator',
				'description' => 'Administrator',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

			[
				'name' => 'HR Manager',
				'display_name' => 'HR Manager',
				'description' => 'HR Manager',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

			[
				'name' => 'Manager',
				'display_name' => 'Team Manager',
				'description' => 'Team Manager',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

			[
				'name' => 'PL',
				'display_name' => 'Project Lead',
				'description' => 'Project Lead',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
			[
				'name' => 'APL',
				'display_name' => 'Associate Project Lead',
				'description' => 'Associate Project Lead',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

			[
				'name' => 'GL',
				'display_name' => 'Group Lead',
				'description' => 'Group Lead',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

			[
				'name' => 'AGL',
				'display_name' => 'Associate Group Lead',
				'description' => 'Associate Group Lead',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

			[
				'name' => 'HR',
				'display_name' => 'HR',
				'description' => 'HR',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
		]);

	}
}
