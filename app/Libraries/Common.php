<?php

namespace App\Libraries;

class Common {

    public static function generateMailTo($mail_object,$template_details) {
        if($template_details['to'] != ''){
            $to_addresses = explode(',',$template_details['to']);
            foreach($to_addresses as $to){
              $mail_object->to($to);
            }
          }
          return $mail_object;
    }

    public static function generateMailCc($mail_object,$template_details) {
        if($template_details['cc'] != ''){
            $ccs = explode(',',$template_details['cc']);
            foreach($ccs as $cc){
              $mail_object->cc($cc);
            }
          }
          return $mail_object;
    }

    public static function generateMailBcc($mail_object,$template_details) {
        if($template_details['bcc'] != ''){
            $bccs = explode(',',$template_details['bcc']);
            foreach($bccs as $bcc){
              $mail_object->bcc($bcc);
            }
          }
          return $mail_object;
    }

    public static function generateMailAttachments($mail_object,$template_details) {
        if($template_details['attachments'] != ''){
            $attachments = explode(',',$template_details['attachments']);
            foreach($attachments as $file){
              $file_path = base_path(config('constants.IMAGE_UPLOAD_PATH').'/'.$file);
              $mail_object->attach($file_path);
            }
          }
          return $mail_object;
    }
}
