<?php

namespace App\Http\Helpers;

// Event for notification email, alert, sms
use App\Events\SendMail;
use App\Models\Chef;
use App\Models\Admin;
use App\Models\LogActivity;
use App\Models\User;
use App\Models\GuestUsers;
use App\Models\TriggeringMessages;
use Auth;
/**
 * Common response trait
 */
trait CommonTrait
{

    /**
     *
     * @param type $template
     * @param type $toIds
     * @param type $tags
     * @return boolean
     */
    public function sendEmailNotification($triggerKey, $toIds = [], $tags = [])
    {
        // get template
        $templateData = TriggeringMessages::where(['trigger' => $triggerKey])->first();

        if (!empty($templateData) && !empty($toIds)) {
            $toIds = array_unique($toIds);

            foreach ($toIds as $id) {
                $user    = User::select(['name', 'email'])
                            ->where(['id' => $id])->first();
                $toEmail = $user->email;

                $toUserFullname    = $tags['USER_NAME']  = $user->name;
                $fromName          = $tags['FROM_NAME'] = config('mail.from.name');
                $fromEmail         = config('mail.from.address');
                $search            = $replace           = [];

                //$tags['SITE_LINK'] = env('ANGULAR_URL');
                //$tags['LOGO']      = config('mail.logo');
                //$tags['YEAR']      = date('Y');

                foreach ($tags as $k => $v) {
                    array_push($search, "##{$k}##");
                    array_push($replace, $v);
                }
                
                $mailData['toName']       = $toUserFullname;
                $mailData['toEmail']      = $toEmail;
                $mailData['fromName']     = $fromName;
                $mailData['fromEmail']    = $fromEmail;
                $mailData['emailSubject'] = nl2br(str_replace($search, $replace, $templateData['email_subject']));
                $mailData['emailContent'] = str_replace($search, $replace, $templateData['email_body']);
                //$template_notification = nl2br(str_replace($search, $replace, $templateData['notification_content']));
                //$template_sms = str_replace($search, $replace, $templateData['sms_content']);
                //attachment

                \Event::fire(new SendMail($mailData));
            }

            return true;
        }
        return false;
    }
    
    /**
     *
     * @param type $template
     * @param type $toIds
     * @param type $tags
     * @return boolean
     */
    public function sendEmailNotificationToEmail($toEmail, $toName, $triggerKey, $tags = [])
    {
        // get template
        $templateData = TriggeringMessages::where(['trigger' => $triggerKey])->first();

        if (!empty($templateData) && $toEmail != '') {

                $toUserFullname    = $toName;
                $fromName          = $tags['FROM_NAME'] = config('mail.from.name');
                $fromEmail         = config('mail.from.address');
                $search            = $replace           = [];

                //$tags['SITE_LINK'] = env('ANGULAR_URL');
                //$tags['LOGO']      = config('mail.logo');
                //$tags['YEAR']      = date('Y');

                foreach ($tags as $k => $v) {
                    array_push($search, "##{$k}##");
                    array_push($replace, $v);
                }
                
                $mailData['toName']       = $toUserFullname;
                $mailData['toEmail']      = $toEmail;
                $mailData['fromName']     = $fromName;
                $mailData['fromEmail']    = $fromEmail;
                $mailData['emailSubject'] = nl2br(str_replace($search, $replace, $templateData['email_subject']));
                $mailData['emailContent'] = str_replace($search, $replace, $templateData['email_body']);
                //$template_notification = nl2br(str_replace($search, $replace, $templateData['notification_content']));
                //$template_sms = str_replace($search, $replace, $templateData['sms_content']);
                //attachment

                \Event::fire(new SendMail($mailData));

            return true;
        }
        return false;
    }

    /**
     * log activity
     * @param type $action_id
     * @param type $action
     * @param type $ipAddress
     * @param type $msg
     */
    public function logactivity($action_id, $action, $ipAddress, $msg = '')
    {
        if(Auth::guard('admin')->check()) {
            $logger_id = Auth::guard('admin')->user()->id;
            $type = 'Admin';
        } else if(Auth::guard('chef')->check()) {
            $logger_id = Auth::guard('chef')->user()->id;
            $type = 'Chef';
        }
        $activity['logger_id'] = $logger_id;
        $activity['message'] = $msg;
        $activity['action'] = $action;
        $activity['action_id'] = $action_id;
        $activity['type'] = $type;
        $activity['ip_address'] = $ipAddress;
        LogActivity::create($activity);
    }
    
    /**
     * get logged user info
     * @return type
     */
    public function getCurrentLoggedUser() {
        if(Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        } else if(Auth::guard('chef')->check()) {
            return Auth::guard('chef')->user();
        }
    }
    

    public function sendEmailNotificationCustom($id, $tags = [], $subject, $content, $type)
    {
        // get template
        // $templateData = EmailTemplate::getTemplate(['email_key' => $template]);

        /*if (!empty($templateData) && !empty($toIds)) {
            $toIds = array_unique($toIds);

            foreach ($toIds as $id) {
                $user    = User::select(['username', 'first_name', 'last_name', 'email'])
                            ->where(['id' => $id])->first();
                $toEmail = $user->email;

                $toUserFullname    = $tags['USERNAME']  = $user->first_name.' '.$user->last_name;
                $fromName          = $tags['FROM_NAME'] = config('mail.from.name');
                $fromEmail         = config('mail.from.address');
                $search            = $replace           = [];

                $tags['SITE_LINK'] = env('ANGULAR_URL');
                $tags['LOGO']      = config('mail.logo');
                $tags['YEAR']      = date('Y');

                foreach ($tags as $k => $v) {
                    array_push($search, "##{$k}##");
                    array_push($replace, $v);
                }

                $mailData['toName']       = $toUserFullname;
                $mailData['toEmail']      = $toEmail;
                $mailData['fromName']     = $fromName;
                $mailData['fromEmail']    = $fromEmail;
                $mailData['emailSubject'] = nl2br(str_replace($search, $replace, $templateData['email_subject']));
                $mailData['emailContent'] = str_replace($search, $replace, $templateData['email_body']);
                //$template_notification = nl2br(str_replace($search, $replace, $templateData['notification_content']));
                //$template_sms = str_replace($search, $replace, $templateData['sms_content']);
                //attachment

                \Event::fire(new SendMail($mailData));
            }

            return true;
        }*/

        if($type == 'chef') {
            $user    = Chef::select(['name', 'email'])
                            ->where(['id' => $id])->first();
        }else{
            $user    = Admin::select(['name', 'email'])
                            ->where(['id' => $id])->first();
        }
        $toEmail = $user->email;

        $toUserFullname    = $user->name;
        $fromName          = config('mail.from.name');
        $fromEmail         = config('mail.from.address');

        $mailData['toName']       = $toUserFullname;
        $mailData['toEmail']      = $toEmail;
        $mailData['fromName']     = $fromName;
        $mailData['fromEmail']    = $fromEmail;
        $mailData['emailSubject'] = $subject;
        $mailData['emailContent'] = $content;
        //$template_notification = nl2br(str_replace($search, $replace, $templateData['notification_content']));
        //$template_sms = str_replace($search, $replace, $templateData['sms_content']);
        //attachment

        \Event::fire(new SendMail($mailData));
        return false;
    }
    
    /**
     * send fcm notification
     * @param type $target
     * @param type $title
     * @param type $description
     */
    public function sendFCM($target, $title, $description)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';
        $server_key = config('app.FIREBASELEGACYSERVERKEY');
        $fields = array();
        $fields['data'] = array("title" => $title, "body" => $description);

        if (is_array($target)) {
            $fields['registration_ids'] = $target;
        } else {
            $fields['to'] = $target;
        }
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        if ($result === FALSE) {
//            $this->model->error($result, 'Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);

//        $this->model->error($result, "");

    }
    
     /**
     * send fcm notification
     * @param type $target
     * @param type $title
     * @param type $description
     */
    public function test_FCM($target, $title, $description)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';
        $server_key = config('app.FIREBASELEGACYSERVERKEY');
        $fields = array();
        $fields['data'] = array("title" => $title, "body" => $description);

        if (is_array($target)) {
            $fields['registration_ids'] = $target;
        } else {
            $fields['to'] = $target;
        }
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        if ($result === FALSE) {
            print_r($result);
        }
        curl_close($ch);

        print_r($result);

    }
    
    
    /**
     * Send notifications
     * @param type $user_id
     * @param type $guest_user_id
     * @param type $trigger
     */
    public function notifyOnOrder($user_id, $guest_user_id, $trigger = "") {
        if($user_id) {
            $curntUsr = User::where(['id' => $user_id])->first();
        } else {
            $curntUsr = GuestUsers::where(['id' => $guest_user_id])->first();
        }
        $triggerMsg = TriggeringMessages::where('trigger', $trigger)->first();
        
        switch($trigger) {
            case "NOTIFY_USER_ON_ORDER_CREATE":
                $this->sendFCM($curntUsr->fcm_token, "ORDER-ACCEPTED", $triggerMsg->notification);
            break;
        }
    }
}
