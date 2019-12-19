<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailQueues extends Model
{
	protected $primaryKey='id';
    protected $fillable = [
        'from', 'to', 'cc', 'bcc','subject','message', 'template', 'template_details', 'attachments','error','error_message','priority','status'
    ];
}
