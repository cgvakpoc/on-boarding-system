<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leads')->delete();

        DB::table('leads')->insert([
            [
                'name' => "Edoardo",
                'designation' => "Lead 1",
                'email_id' => "Gennaro.Quitzon@hotmail.com",
                'created_by' => 1,
                'updated_by' => 1,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Marcella',
                'designation' => 'Lead 2',
                'email_id' => 'Ernest_Hermann85@yahoo.com',
                'created_by' => 1,
                'updated_by' => 1,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Elena',
                'designation' => 'Lead 3',
                'email_id' => 'Delbert.Beier@hotmail.com',
                'created_by' => 1,
                'updated_by' => 1,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Elisa',
                'designation' => 'Lead 4',
                'email_id' => 'Keaton12@yahoo.com',
                'created_by' => 1,
                'updated_by' => 1,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Romano',
                'designation' => 'Lead 5',
                'email_id' => 'Corbin64@yahoo.com',
                'created_by' => 1,
                'updated_by' => 1,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
