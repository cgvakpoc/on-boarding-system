<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('permissions')->delete();

		DB::table('permissions')->insert([

			[
				'name' => 'create department',
				'display_name' => 'Create department',
				'description' => 'Create department',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

			[
				'name' => 'Edit department',
				'display_name' => 'Edit department',
				'description' => 'Edit department',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

			[
				'name' => 'Delete department',
				'display_name' => 'Delete department',
				'description' => 'Delete department',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

			[
				'name' => 'List Department',
				'display_name' => 'List Department',
				'description' => 'List Department',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],

		]);
	}
}
