<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

    if (! function_exists('smtp_send_one')) {
        function smtp_send_one($email, $fullname, $mailContent, $mailSubject, $mailAttach = '')
        {

            $db = \Config\Database::connect();
            $builder = $db->table('sender_settings');
            $query = $builder->get();
            $query = $query->getResultArray();

            $sender_domain = 'lourer.com';
            $sender_host = 'smtp.namag.com';
            $sender_port = 587;
            $sender_username = 'hampig@namag.com';
            $sender_password = 'nonono';
            $sender_from_name = 'SaaS - One Mail Send';
            $sender_reply_mail = 'hampig@namag.com';
            $sender_reply_name = 'SaaS - Reply IUD NULL';
            $sender_sleep = 1;
            $sender_limit_per_connection = 20;
            $sender_unsubscribe_link = $sender_domain . "/unsubscribe.php";


            // PHPMailer OBJECT
            $mail = new PHPMailer(true);

            // FILE GET CONTENT
            // $body = file_get_contents('contents.html');

            // SMTP CONFIGURATION
            $mail->isSMTP();
            $mail->XMailer = 'NAMAG';
            $mail->Host = $sender_host;
            $mail->SMTPAuth = true;
            $mail->SMTPAutoTLS = false;
            $mail->Username = $sender_username;
            $mail->Password = $sender_password;
            $mail->SMTPSecure = 'ENCRYPTION_STARTTLS';
            $mail->Port = $sender_port;
            $mail->SMTPKeepAlive = true;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Sender = $sender_username;
            $mail->setFrom($sender_username, $sender_from_name);
            $mail->addReplyTo($sender_reply_mail, $sender_reply_name);
            // $mail->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help'];

            $mail->addAddress($email, $fullname);
            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->AddCustomHeader("List-Unsubscribe: <mailto:" . $sender_username . "?subject=unsubscribe>, <http://www." . $sender_unsubscribe_link . ">");
            $mail->AltBody = strip_tags($mailContent);
            $mail->Body = $mailContent;

            try {
                $mail->send();
            } catch (Exception $e) {
                // echo $e->getMessage();
                $mail->ErrorInfo;
                $mail->smtp->reset();
            }

            // WAIT AFTER EACH MAIL SENT
            sleep($sender_sleep);

            // CLEAR
            $mail->ClearAddresses();
            $mail->ClearAllRecipients();
            $mail->ClearAttachments();

            $mail->smtpClose();

        }
    }


        if (! function_exists('smtp_send')){
            function smtp_send($senderID, $subscribersList,$mailContent, $mailSubject, $mailAttach=''){

//            $query=$CI->SenderSettingsModel->getSettings($senderID);
                $db      = \Config\Database::connect();
                $builder = $db->table('sender_settings');
                $query = $builder->get();
                $query = $query->getResultArray();

                if(!empty($senderID)){
                    foreach ($query as $row){
                        $sender_domain                  = $row['sender_domain'];
                        $sender_host                    = $row['sender_host'];
                        $sender_port                    = $row['sender_port'];
                        $sender_username                = $row['sender_username'];
                        $sender_password                = $row['sender_password'];
                        $sender_from_name               = $row['sender_from_name'];
                        $sender_reply_mail              = $row['sender_reply_mail'];
                        $sender_reply_name              = $row['sender_reply_name'];
                        $sender_sleep                   = $row['sender_sleep'];
                        $sender_limit_per_connection    = $row['sender_limit_per_connection'];
                        $sender_unsubscribe_link        = $row['sender_unsubscribe_link'];
                    }
                }else{
                    $sender_domain                  = 'lourer.com';
                    $sender_host                    = 'smtp.namag.com';
                    $sender_port                    = 587;
                    $sender_username                = 'hampig@namag.com';
                    $sender_password                = 'nonono';
                    $sender_from_name               = 'SaaS - Newsletters IUD NULL';
                    $sender_reply_mail              = 'hampig@namag.com';
                    $sender_reply_name              = 'SaaS - Reply IUD NULL';
                    $sender_sleep                   = 1;
                    $sender_limit_per_connection    = 20;
                    $sender_unsubscribe_link        = $sender_domain."/unsubscribe.php";
                };

                // PHPMailer OBJECT
                $mail = new PHPMailer(true);

                // FILE GET CONTENT
                // $body = file_get_contents('contents.html');

                // SMTP CONFIGURATION
                $mail->isSMTP();
                $mail->XMailer          = 'NAMAG';
                $mail->Host             = $sender_host;
                $mail->SMTPAuth         = true;
                $mail->SMTPAutoTLS      = false;
                $mail->Username         = $sender_username;
                $mail->Password         = $sender_password;
                $mail->SMTPSecure       = 'ENCRYPTION_STARTTLS';
                $mail->Port             = $sender_port;
                $mail->SMTPKeepAlive    = true;
                $mail->CharSet          = 'UTF-8';
                $mail->Encoding         = 'base64';
                $mail->Sender           = $sender_username;
                $mail->setFrom($sender_username, $sender_from_name);
                $mail->addReplyTo($sender_reply_mail,$sender_reply_name);

                // $mail->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help'];

                // ADD A RECIPIENT
//             $users = [
//                 ['email' => 'hampig@namag.com', 'name' => 'HELLO'],
//                 // ['email' => 'testik@dispostable.com', 'name' => 'HELLO'],
//                 // ['email' => 'htalatinian@gmail.com', 'name' => 'Hampik TALATINIAN'],
//                 // ['email' => 'hampik.talatinian@aklor.com', 'name' => 'Hampik TALATINIAN']
//             ];
//             $subscribersList=$CI->SenderSettingsModel->getSubscribers();

                foreach ($subscribersList as $subscriber) {
                    if ($subscriber['subscriber_lastname'] == "")
                    {
                        $mail->addAddress($subscriber['subscriber_email'], $subscriber['subscriber_firstname']);
                    }else if($subscriber['subscriber_firstname'] == "")
                    {
                        $mail->addAddress($subscriber['subscriber_email'], $subscriber['subscriber_lastname']);
                    }else if($subscriber['subscriber_firstname'] == "" || $subscriber['subscriber_lastname'] == "")
                    {
                        $mail->addAddress($subscriber['subscriber_email']);
                    }else{
                        $mail->addAddress($subscriber['subscriber_email'], $subscriber['subscriber_firstname'].' '.$subscriber['subscriber_lastname']);
                    }

                    $mail->isHTML(true);
                    $mail->Subject          =$mailSubject;
                    $mail->AddCustomHeader("List-Unsubscribe: <mailto:".$sender_username."?subject=unsubscribe>, <http://www.".$sender_unsubscribe_link.">");
                    $mail->AltBody          = strip_tags($mailContent);
                    $mail->Body             = $mailContent;

                    try {
                        $mail->send();
                    }catch (Exception $e)
                    {
                        // echo $e->getMessage();
                        $mail->ErrorInfo;
                        $mail->smtp->reset();
                    }

                    // WAIT AFTER EACH MAIL SENT
                    sleep($sender_sleep);

                    // CLEAR
                    $mail->ClearAddresses();
                    $mail->ClearAllRecipients();
                    $mail->ClearAttachments();

                }

                $mail->smtpClose();

            }

    }



