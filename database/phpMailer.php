
<?php
require_once 'dbConnection.php';  // Database connection file
require_once 'dbHandler.php';

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

class mail{
public function send_auth_email($email, $username){
//Create an instance; passing true enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    

    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'giftgichuhi4138@gmail.com';                     //SMTP username
    $mail->Password   = 'kzfvfdwaoqqlxzjq';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

    //Recipients
    $mail->setFrom('giftgichuhi4138@gmail.com', 'Alejandro from AAA');
    $mail->addAddress($email, $username);     //Add a recipient
    $mail->addReplyTo('info@example.com', 'Information');
    $random= rand(100000,999999);
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Welcome to Translvania hotel';
    $mail->Body    = 'YOUR authentication is almost complete. Please fill the otp field with the digit below '.$random;
    $mail->AltBody = 'Follow this <a href="localhost:3000/index.php">Redirect</a> to go to AAA website';

    $mail->send();
    if (!$mail->send()) {
        return false; // Return false on failure
      }
      return ['random' => $random, 'success' => true];
} catch (Exception $e) {
    
    return false;
}
}
}