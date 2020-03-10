<?php

use Illuminate\Database\Seeder;
Use App\Document;


class DocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $document = new Document();
        $document->title = 'Offer breakup sheet';
        $document->save();
        $document = new Document();
        $document->title = 'One page confirmation letter';
        $document->save();
        $document = new Document();
        $document->title = 'Joining commitment';
        $document->save();
    }
}
