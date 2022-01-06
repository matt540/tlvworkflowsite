<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repository\EmailSendRecordRepository as email_send_record_repo;
use App\Repository\UserRepository as user_repo;
use Tymon\JWTAuth\Facades\JWTAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Log;



class EmailController extends Controller
{

    public function __construct(email_send_record_repo $email_send_record_repo, user_repo $user_repo)
    {

        $this->user_repo = $user_repo;

        $this->email_send_record_repo = $email_send_record_repo;
    }

    public function sendMail($email, $subject, $message, $attachments = array(), $bccs = array(), $ccs = array(), $other_emails = array())
    {
       // $email = 'webdeveloper1011@gmail.com';
        $mail = null;

        $mail = new PHPMailer(true); // notice the \  you have to use root namespace here

        try {
           // $mail->SMTPDebug = 1;
//            $mail->isSMTP();
//            $mail->Host = 'smtp.dreamhost.com'; // smtp host
//            $mail->SMTPAuth = true;
//            $mail->Username = 'developer@esparkbizmail.com'; // sender username
//            $mail->Password = 'Chits@1.'; // sender password
//            $mail->SMTPSecure = 'ssl'; // encryption - ssl/tls
//            $mail->Port = 465; // port - 587/465

            $mail->SMTPAuth = true;  // use smpt auth
            $mail->Host = 'smtp.mandrillapp.com';
            $mail->Port = 587; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'The Local Vault';
            $mail->Password = 'NurhoIS1lMhoQLWKep1ebA';

            $folder_path = '../Uploads/default_pdf/';

            if (count($attachments) > 0) {
                foreach ($attachments as $key => $value) {
                    $mail->AddAttachment($folder_path . $value, 'File');
                }
            }
      //      $mail->setFrom("developer@esparkbizmail.com", "The Local Vault");
            $mail->setFrom("sell@thelocalvault.com", "The Local Vault");
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            $mail->addAddress($email);
            $mail->addBCC('webdeveloper1011@gmail.com');

            if (isset($bccs) && count($bccs) > 0) {
                foreach ($bccs as $key => $email_address) {
                    $mail->addBCC($email_address, 'The Local Vault');
                }
            }

            if (isset($ccs) && count($ccs) > 0) {
                foreach ($ccs as $key => $email_address2) {
                    $mail->addCC($email_address2, 'The Local Vault');
                }
            }

            if (isset($other_emails) && count($other_emails) > 0) {
                foreach ($other_emails as $key => $email_address3) {
                    $mail->addAddress($email_address3);
                }
            }

            if ($mail->send()) {

                try {
                    $data = [];
                    $data['created_by'] = JWTAuth::parseToken()->authenticate();
                    $data['email'] = $email;
                    $data['subject'] = $subject;
                    $data['body'] = $message;
                    $email_obj = $this->email_send_record_repo->prepareData($data);
                    $this->email_send_record_repo->create($email_obj);

                } catch (\RuntimeException $e) {
                    Log::info($e);
                    // Content is not encrypted.
                } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    Log::info($e);
                   // Content is not encrypted.
                } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                    Log::info($e);
                } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    Log::info($e);
                }
            }
        } catch (phpmailerException $e) {
            Log::info($e);
            return 0;
        } catch (Exception $e) {
           Log::info($e);
            return 0;
        }

        return 1;
    }

    public function sendMailONLY($email, $subject, $message, $attachments = array(), $bccs = array(), $ccs = array(), $other_emails = array())
    {
     //   $email = 'webdeveloper1011@gmail.com';

        $mail = null;
        $mail = new PHPMailer(true); // notice the \  you have to use root namespace here
        try {
           // $mail->SMTPDebug = 1;
//            $mail->isSMTP();
//            $mail->Host = 'smtp.dreamhost.com'; // smtp host
//            $mail->SMTPAuth = true;
//            $mail->Username = 'developer@esparkbizmail.com'; // sender username
//            $mail->Password = 'Chits@1.'; // sender password
//            $mail->SMTPSecure = 'ssl'; // encryption - ssl/tls
//            $mail->Port = 465; // port - 587/465

            $mail->SMTPAuth = true;  // use smpt auth
            $mail->Host = 'smtp.mandrillapp.com';
            $mail->Port = 587; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'The Local Vault';
            $mail->Password = 'NurhoIS1lMhoQLWKep1ebA';

            $folder_path = '../Uploads/default_pdf/';

            if (count($attachments) > 0) {
                foreach ($attachments as $key => $value) {
                    $mail->AddAttachment($folder_path . $value, 'File');
                }
            }

    //        $mail->setFrom("developer@esparkbizmail.com", "The Local Vault");

            $mail->setFrom("sell@thelocalvault.com", "The Local Vault");
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            $mail->addAddress($email);
            $mail->addBCC('webdeveloper1011@gmail.com');

            if (isset($bccs) && count($bccs) > 0) {
                foreach ($bccs as $key => $email_address) {
                    $mail->addBCC($email_address, 'The Local Vault');
                }
            }
            if (isset($ccs) && count($ccs) > 0) {
                foreach ($ccs as $key => $email_address2) {
                    $mail->addCC($email_address2, 'The Local Vault');
                }
            }
            if (isset($other_emails) && count($other_emails) > 0) {
                foreach ($other_emails as $key => $email_address3) {
                    $mail->addAddress($email_address3);
                }
            }
            if ($mail->send()) {
                try {
                    $data = [];
                    $data['created_by'] = JWTAuth::parseToken()->authenticate();
                    $data['email'] = $email;
                    $data['subject'] = $subject;
                    $data['body'] = $message;
                    $email_obj = $this->email_send_record_repo->prepareData($data);
                    $this->email_send_record_repo->create($email_obj);
                } catch (\RuntimeException $e) {
                    // Content is not encrypted.
                } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    // Content is not encrypted.
                } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                }
            }
        } catch (phpmailerException $e) {
            return 0;
        } catch (Exception $e) {
            return 0;
        }
        return 1;
    }

    public function sendMailSellerAgreement($email, $subject, $message, $attachments = array(), $bccs = array(), $ccs = array(), $other_emails = array())
    {
       // $email = 'webdeveloper1011@gmail.com';

        $mail = null;
        $mail = new PHPMailer(true); // notice the \  you have to use root namespace here
        try {

           // $mail->SMTPDebug = 1;
//            $mail->isSMTP();
//            $mail->Host = 'smtp.dreamhost.com'; // smtp host
//            $mail->SMTPAuth = true;
//            $mail->Username = 'developer@esparkbizmail.com'; // sender username
//            $mail->Password = 'Chits@1.'; // sender password
//            $mail->SMTPSecure = 'ssl'; // encryption - ssl/tls
//            $mail->Port = 465; // port - 587/465

            $mail->SMTPAuth = true;  // use smpt auth
            $mail->Host = 'smtp.mandrillapp.com';
            $mail->Port = 587; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'The Local Vault';
            $mail->Password = 'NurhoIS1lMhoQLWKep1ebA';

            $folder_path = '../Uploads/default_pdf/';

            if (count($attachments) > 0) {
                foreach ($attachments as $key => $value) {
                    $mail->AddAttachment($folder_path . $value, 'File');
                }
            }
      //      $mail->setFrom("developer@esparkbizmail.com", "The Local Vault");

            $mail->setFrom("sell@thelocalvault.com", "The Local Vault");
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            $mail->addAddress($email);
            $mail->addBCC('webdeveloper1011@gmail.com');
            if (isset($bccs) && count($bccs) > 0) {
                foreach ($bccs as $key => $email_address) {
                    $mail->addBCC($email_address, 'The Local Vault');
                }
            }
            if (isset($ccs) && count($ccs) > 0) {
                foreach ($ccs as $key => $email_address2) {
                    $mail->addCC($email_address2, 'The Local Vault');
                }
            }
            if (isset($other_emails) && count($other_emails) > 0) {
                foreach ($other_emails as $key => $email_address3) {
                    $mail->addAddress($email_address3);
                }
            }
            if ($mail->send()) {
            }
        } catch (phpmailerException $e) {
            return 0;
        } catch (Exception $e) {
            return 0;
        }
        return 1;
    }

    public function sendMail1($email, $subject, $message, $attachments = array(), $bccs = array())
    {
      //  $email = 'webdeveloper1011@gmail.com';

        $mail = null;
        $mail = new PHPMailer(true); // notice the \  you have to use root namespace here
        try {
          //  $mail->SMTPDebug = 1;
//            $mail->isSMTP();
//            $mail->Host = 'smtp.dreamhost.com'; // smtp host
//            $mail->SMTPAuth = true;
//            $mail->Username = 'developer@esparkbizmail.com'; // sender username
//            $mail->Password = 'Chits@1.'; // sender password
//            $mail->SMTPSecure = 'ssl'; // encryption - ssl/tls
//            $mail->Port = 465; // port - 587/465

            $mail->SMTPAuth = true;  // use smpt auth
            $mail->Host = 'smtp.mandrillapp.com';
            $mail->Port = 587; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'The Local Vault';
            $mail->Password = 'NurhoIS1lMhoQLWKep1ebA';

         //   $mail->setFrom("developer@esparkbizmail.com", "The Local Vault");

           $mail->setFrom("sell@thelocalvault.com", "The Local Vault");
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
//            $mail->addAddress('sell@thelocalvault.com');
//            $mail->addAddress('matt@540designstudio.com');
//            $mail->addAddress($email);
            if (isset($email) && count($email) > 0) {
                foreach ($email as $key => $emails) {
                    $mail->addAddress($emails);
                }
            }

            if (isset($bccs) && count($bccs) > 0) {
                foreach ($bccs as $key => $email) {
                    $mail->addCC($email, 'The Local Vault');
                }
            }
            if ($mail->send()) {
            }
        } catch (phpmailerException $e) {
            return 0;
        } catch (Exception $e) {
            return 0;
        }
        return 1;
    }

    public function sendMailWithMultipleAttachments($email, $subject, $message, $path, $attachments = array(), $directors = array())
    {
        $email = 'webdeveloper1011@gmail.com';

        $mail = null;
        $mail = new PHPMailer(true); // notice the \  you have to use root namespace here
        try {
         //   $mail->SMTPDebug = 1;
//            $mail->isSMTP();
//            $mail->Host = 'smtp.dreamhost.com'; // smtp host
//            $mail->SMTPAuth = true;
//            $mail->Username = 'developer@esparkbizmail.com'; // sender username
//            $mail->Password = 'Chits@1.'; // sender password
//            $mail->SMTPSecure = 'ssl'; // encryption - ssl/tls
//            $mail->Port = 465; // port - 587/465

            $mail->SMTPAuth = true;  // use smpt auth
            $mail->Host = 'smtp.mandrillapp.com';
            $mail->Port = 587; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'The Local Vault';
            $mail->Password = 'NurhoIS1lMhoQLWKep1ebA';

            if (count($attachments) > 0) {
                foreach ($attachments as $key => $value) {
                    $mail->AddAttachment($path . $value, 'File');
                }
            }
         //   $mail->setFrom("developer@esparkbizmail.com", "The Local Vault");

            $mail->setFrom("sell@thelocalvault.com", "The Local Vault");
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            $mail->addAddress($email);
            if (count($directors) > 0) {
                foreach ($directors as $key => $value) {
                    $mail->addCC($value['email'], $value['fullname']);
                }
            }
                $mail->addBCC('production@thelocalvault.com');
            $mail->send();
        } catch (phpmailerException $e) {
            return 0;
        } catch (Exception $e) {
            return 0;
        }
        return 1;
    }
}
