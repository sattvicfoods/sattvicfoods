<?php

require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = "smtp.softprodigy.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "do.not.reply@softprodigy.com";  // SMTP username
$mail->Password = "Pinu!mglr5"; // SMTP password
                     // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->From = 'do.not.reply@softprodigy.com';
$mail->FromName = 'Mailer';
$mail->addAddress('manita_kumari@softprodigy.com');     // Add a recipient
             // Name is optional


$mail->addAttachment('/var/www/html/PHPMailer/order_146.pdf');         // Add attachments
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

?>
