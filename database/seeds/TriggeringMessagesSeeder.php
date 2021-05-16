<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TriggeringMessagesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedArr = array(
            [
                'trigger' => 'MOBILE_VERIFICATION',
                'sms' => '##OTP## is your one time password for verifying mobile',
                'notification' => null,
                'email_subject' => null,
                'email_body' => null,
                'status' => 'Active',
            ],
            [
                'trigger' => 'FORGOT_PASSWORD',
                'sms' => '##OTP## is your one time password for changing password',
                'notification' => null,
                'email_subject' => null,
                'email_body' => null,
                'status' => 'Active',
            ],
            [
                'trigger' => 'USER_REGISTRAION',
                'sms' => null,
                'notification' => null,
                'email_subject' => 'Thank you for Registration',
                'email_body' => "Hello ##USER_NAME##,<br/> Thank you for Registration.<br/> "
                . "<br/> Email: ##USER_EMAIL##<br/>"
                . "<br/> Email: ##PASSWORD##<br/>"
                . "<br/> From: ##SIGNUP_FROM##<br/>"
                . "<br/> Date: ##CREATED_AT##<br/> Thanks",
                'status' => 'Active',
            ],
            [
                'trigger' => 'NOTIFY_ADMIN_USER_REGISTRAION',
                'sms' => null,
                'notification' => null,
                'email_subject' => 'New User Registered',
                'email_body' => "Hello Admin,<br/> New User Registered.<br/> "
                . "<br/> Name: ##USER_NAME##<br/>"
                . "<br/> Email: ##USER_EMAIL##<br/>"
                . "<br/> From: ##SIGNUP_FROM##<br/>"
                . "<br/> Date: ##CREATED_AT##<br/> Thanks",
                'status' => 'Active',
            ]
            
        );

        foreach ($seedArr as $seed) {
            DB::table('triggering_messages')->insert([//,
                'trigger' => $seed['trigger'],
                'sms' => $seed['sms'],
                'notification' => $seed['notification'],
                'email_subject' => $seed['email_subject'],
                'email_body' => $seed['email_body'],
                'status' => $seed['status']
            ]);
        }
    }
}
