<?php 

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';

    function escape($string){
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }

    
    function sendMail($data){       
        $message = $data;
        $subject = 'Balotra Offline Units Report !';  
        
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = "smtp-relay.sendinblue.com";  //change your host name
        $mail->SMTPAuth = TRUE;
        $mail->Username = "admin@visionworldtech.com"; // change your smtp email
        $mail->Password = "Zs4Cx2GgQSRMnI0K";   //change your smtp password
        $mail->Port = 587;
        $mail->addAttachment("files/Offline-Units-balotra.xls");
        $mail->From = "admin@visionworldtech.com"; //change your server email
        $mail->FromName = "Balotra-SCADA";
        $mail->addAddress('admin@visionworldtech.com', 'Vision World Tech Pvt. Ltd.');
        //$mail->addAddress('kumar.sunil.nat@gmail.com', 'Vision World Tech Pvt. Ltd.'); 
        $mail->addAddress('balotrascada@gmail.com', 'Vision World Tech Pvt. Ltd.');
        $mail->isHTML(true);
        $mail->Subject =  $subject;
        $mail->Body =  $message;
        
        if (!$mail->send()) {
            return false;
        }else{
            return true;
        }
        
    }