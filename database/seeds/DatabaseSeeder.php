<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//$this->call(UsersTableSeeder::class);
		//$this->call(RolesTableSeeder::class);
		//$this->call(PermissionsTableSeeder::class);
		//$this->call(ProficiencyTableSeeder::class);
		//$this->call(LeadsTableSeeder::class);
		//$this->call(RequirementTypeSeeder::class);
		$this->call(DocumentsTableSeeder::class);
	}
}
