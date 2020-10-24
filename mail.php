<?php
$to = "usman.khalid@secp.gov.pk";
$subject = "SECP My subject";
$txt = "CHal Raha hai boss Cron!";
$headers = "From: notification.servicedesk@secp.gov.pk";
echo mail($to,$subject,$txt,$headers);
/*error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
echo "vcbcv";
require_once "Mail.php";
 
 $from = "Sandra Sender <notification.servicedesk@secp.gov.pk>";
 $to = "Ramona Recipient <haseeb.developer@gmail.com>";
 $subject = "Hi!";
 $body = "Hi,\n\nHow are you?";
 
 $host = "mail.secp.gov.pk";
 $username = "";
 $password = "";
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 echo $smtp;
 $mail = $smtp->send($to, $headers, $body);
 echo "123";
 if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
  } else {
   echo("<p>Message successfully sent!</p>");
  }*/

?> 
