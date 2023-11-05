<?php

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $text = $_POST['text'];
    $mail = $_POST['mail']
;}



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require_once 'php_modules/Exception.php';
require_once 'php_modules/PHPMailer.php';
require_once 'php_modules/SMTP.php';
require_once ('head.php');

try{
$mail = new PHPMailer(true);
//PHPMailer Object``$mail = new PHPMailer(true); //Argument true in constructor enables exceptions`

//From email address and name
$mail->From = "from@is.koroteevav.ru";
$mail->FromName = "Жалоба на магазин id = " . $id;

//To address and name
$mail->addAddress($_POST['mail']); //Recipient name is optional


//CC and BCC
$mail->addCC("cc@example.com");
$mail->addBCC("bcc@example.com");

//Send HTML or Plain Text email
$mail->isHTML(true);

// $mail->addAttachment("s.png", "s.png");
// $mail->addAttachment("s2.png", "s2.png");
$directory = 'img/' . $id . '/';
$files = glob($directory . '*');

foreach ($files as $file) {
    if (is_file($file)) {
        $mail->addAttachment($file, basename($file));
    }
}

//encoding
$mail->CharSet = "utf-8";

$mail->Subject = $title;
$mail->Body = $text;
$mail->AltBody = "This is the plain text version of the email content";
$mail->send();
echo '<h1 class="alert alert-success container text-center"> Сообщение отправлено </h1>';
}
catch(Exception $e){
    echo '<h1 class="alert alert-danger container text-center"> Сообщение не было отправлено </h1>';

}










?>