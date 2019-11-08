<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use App\Mail\BirthdayWishes;
use App\Mail\DefaultTemplate;
use App\Mail\EmailError;
use App\EmailQueues;

class SendEmail extends Controller
{
    public function sendRegularEmails()
    {
        $email_details = EmailQueues::where(array('status' => 0, 'error' => 0))
            ->orderBy('priority', 'asc')
            ->take(10)
            ->get()->toarray();

        if (!empty($email_details)) {
            foreach ($email_details as $details) {
                $template = ($details['message'] != '') ? 'default' : $details['template'];
                if ($template != 'default') {
                    $details['template_details'] = json_decode($details['template_details'], true);
                }
                $update_status = EmailQueues::find($details['id']);
                $is_invalid_template = false;
                try {
                    switch ($template) {
                        case 'birthday_wishes':
                            Mail::send(new BirthdayWishes($details));
                            break;
                        case 'default':
                            Mail::send(new DefaultTemplate($details));
                            break;
                        default:
                            $is_invalid_template = true;
                            $update_status->error = 1;
                            $update_status->error_message = 'invalid template';
                            $update_status->save();
                            break;
                    }
                    if ($is_invalid_template) {
                        continue;
                    }
                    $update_status->status = 1;
                } catch (\Exception $e) {
                    $update_status->error = 1;
                    $update_status->error_message = $e->getMessage();
                }
                $update_status->save();
            }
        }
    }

    public function reportFailedEmails()
    {
        $email_details = EmailQueues::where(array('status' => 0, 'error' => 1))
            ->orderBy('priority', 'asc')
            ->take(10)
            ->get()->toarray();
        if (!empty($email_details)) {
            foreach ($email_details as $details) {
                $update_status = EmailQueues::find($details['id']);
                Mail::send(new EmailError($details));
                $update_status->status = 1;
                $update_status->save();
            }
        }
    }

    public function testmail()
    {
        // Route::get('testmail', 'SendEmail@testmail');
        // Route::get('insertEmails', 'SendEmail@insertEmails');
        // Route::get('generateView', 'SendEmail@generateView');
        // Route::get('sendRegularEmails', 'SendEmail@sendRegularEmails');
        // Route::get('reportFailedEmails', 'SendEmail@reportFailedEmails');

        try {
            $comment = json_decode('{"name":"Mr. Test User (S/W)","message":"On This Special Day As You Celebrate Your Birthday Here’s Wishing You a Whole Lotta Happiness And Sweet Surprises. Happy Birthday 1!!!","image":"birthday_cards/card1.jpg"}', true);
            //    $comment['attachments'] = "birthday_cards/card1.jpg,birthday_cards/card2.jpg";
            //    $comment['cc'] = "testcc1@yopmail.com,testcc2@yopmail.com";
            //    $comment['bcc'] = "testbcc1@yopmail.com,testbcc2@yopmail.com";
            //    $comment['from'] = "test@test.com";

            $comment['attachments'] = "";
            $comment['cc'] = "";
            $comment['bcc'] = "";
            $comment['from'] = "testmail52101@gmail.com";
            $comment['to'] = "testmail5210@gmail.com";

            $comment['subject'] = "LARAVEL TEST MAIL 222";
            echo '<pre>';
            print_r($comment);
            die;
            Mail::send(new BirthdayWishes($comment));
        } catch (\Exception $e) {
            echo '111<pre>';
            print_r($e->getMessage());
        }
    }

    public function generateView()
    {
        echo url(config('constants.IMAGE_UPLOAD_PATH'));
        echo base_path('uploads');
        die;
        echo config('constants.IMAGE_UPLOAD_PATH');
        die;
        $BirthdayWishes['template_details'] = json_decode('{"name":"Mr. Test User (S/W)","message":"On This Special Day As You Celebrate Your Birthday Here’s Wishing You a Whole Lotta Happiness And Sweet Surprises. Happy Birthday 1!!!","image":"birthday_cards/card1.jpg"}', true);
        echo '<pre>';
        print_r($BirthdayWishes);
        echo asset('uploads' . $BirthdayWishes['template_details']['image']);
        die;
        return view('mail.birthday_wishes', $BirthdayWishes);
    }

    public function insertEmails()
    {

        // With template, attachment and priority 1
        $details = array(
            'from' => 'on-boarding-test@gmail.com',
            'to' => 'testmail5210@gmail.com,testing.useonly2@gmail.com',
            'cc' => 'testcc1@yopmail.com,testcc2@yopmail.com',
            'bcc' => 'testbcc1@yopmail.com,testbcc2@yopmail.com',
            'subject' => 'Template - Birthday Wishes',
            'message' => '',
            'template' => 'birthday_wishes',
            'template_details' => '{"name":"Mr. Test User (S/W)","message":"On This Special Day As You Celebrate Your Birthday Here’s Wishing You a Whole Lotta Happiness And Sweet Surprises. Happy Birthday !!!","image":"birthday_cards/card1.jpg"}',
            'attachments' => 'birthday_cards/card1.jpg,birthday_cards/card2.jpg',
            'error_message' => '',
            'priority' => 1,
        );
        EmailQueues::create($details);

        // With raw text and priority 2
        $details = array(
            'from' => 'on-boarding-test@gmail.com',
            'to' => 'testmail5210@gmail.com',
            'cc' => 'testing.useonly2@gmail.com',
            'bcc' => 'testing.useonly5@gmail.com',
            'subject' => 'Raw Text - Birthday Wishes',
            'message' => 'Wish u happy birthday',
            'template' => '',
            'template_details' => '',
            'attachments' => '',
            'error_message' => '',
            'priority' => 2,
        );
        EmailQueues::create($details);

        // With template, attachment and priority 2 - error
        $details = array(
            'from' => '',
            'to' => 'testmail5210@gmail.com,testing.useonly2@gmail.com',
            'cc' => 'testcc2@yopmail.com,testcc3@yopmail.com',
            'bcc' => 'testbcc2@yopmail.com,testbcc3@yopmail.com',
            'subject' => 'Template - Birthday Wishes 2',
            'message' => '',
            'template' => 'birthday_wishes',
            'template_details' => '{"name":"Mr. Test User (S/W)","message":"On This Special Day As You Celebrate Your Birthday Here’s Wishing You a Whole Lotta Happiness And Sweet Surprises. Happy Birthday !!!","image":"birthday_cards/card1.jpg"}',
            'attachments' => 'birthday_cards/card1.jpg,birthday_cards/card2.jpg',
            'error_message' => '',
            'priority' => 2,
        );
        EmailQueues::create($details);

        // With raw text and priority 2
        $details = array(
            'from' => 'on-boarding-test@gmail.com',
            'to' => 'testmail5210@gmail.com',
            'cc' => 'testing.useonly2@gmail.com',
            'bcc' => 'testing.useonly5@gmail.com',
            'subject' => 'Raw Text - Birthday Wishes',
            'message' => 'Wish u happy birthday 2',
            'template' => '',
            'template_details' => '',
            'attachments' => '',
            'error_message' => '',
            'priority' => 2,
        );
        EmailQueues::create($details);


        // With template, attachment and priority 2
        $details = array(
            'from' => 'on-boarding-test@gmail.com',
            'to' => 'testmail5210@gmail.com,testing.useonly2@gmail.com',
            'cc' => 'testcc3@yopmail.com,testcc4@yopmail.com',
            'bcc' => 'testbcc3@yopmail.com,testbcc4@yopmail.com',
            'subject' => 'Template - Birthday Wishes 3',
            'message' => '',
            'template' => 'birthday_wishes',
            'template_details' => '{"name":"Mr. Test User (S/W)","message":"On This Special Day As You Celebrate Your Birthday Here’s Wishing You a Whole Lotta Happiness And Sweet Surprises. Happy Birthday !!!","image":"birthday_cards/card1.jpg"}',
            'attachments' => 'birthday_cards/card1.jpg,birthday_cards/card2.jpg',
            'error_message' => '',
            'priority' => 2,
        );
        EmailQueues::create($details);

        // With raw text and priority 1
        $details = array(
            'from' => 'on-boarding-test@gmail.com',
            'to' => 'testmail5210@gmail.com',
            'cc' => 'testing.useonly2@gmail.com',
            'bcc' => 'testing.useonly5@gmail.com',
            'subject' => 'Raw Text - Birthday Wishes',
            'message' => 'Wish u happy birthday 3',
            'template' => '',
            'template_details' => '',
            'attachments' => '',
            'error_message' => '',
            'priority' => 1,
        );
        EmailQueues::create($details);


        //Error - invalid template
        $details = array(
            'from' => 'on-boarding-test@gmail.com',
            'to' => 'testmail5210@gmail.com',
            'cc' => 'testing.useonly2@gmail.com',
            'bcc' => 'testing.useonly5@gmail.com',
            'subject' => 'Raw Text - Birthday Wishes',
            'message' => '',
            'template' => '123',
            'template_details' => '',
            'attachments' => '',
            'error_message' => '',
            'priority' => 1,
        );
        EmailQueues::create($details);
    }
}
