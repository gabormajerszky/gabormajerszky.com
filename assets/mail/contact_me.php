<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "./PHPMailer/Exception.php";
require "./PHPMailer/PHPMailer.php";
require "./PHPMailer/SMTP.php";

if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["phone"]) || empty($_POST["message"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  http_response_code(500);
  exit();
}

$config = parse_ini_file("../../config.ini");
$credentials = parse_ini_file($config["path"]);

$name = strip_tags(htmlspecialchars($_POST["name"]));
$email = strip_tags(htmlspecialchars($_POST["email"]));
$phone = strip_tags(htmlspecialchars($_POST["phone"]));
$message = strip_tags(htmlspecialchars($_POST["message"]));

// Passing true enables exceptions
$mail = new PHPMailer(true);

try {  
  $mail->isSMTP();
  $mail->Host = "smtp.gmail.com";
  $mail->SMTPAuth = true;
  $mail->Username = $credentials["username"];
  $mail->Password = $credentials["password"];
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port = 465;
  $mail->CharSet = "UTF-8";
  $mail->setFrom("noreply@gabormajerszky.com", "gabormajerszky.com");
  $mail->addAddress("majerszkygabor@gmail.com");
  $mail->addReplyTo("$email", "$name");
  $mail->isHTML(true);
  $mail->Subject = "Contact Form Message from $name";
  $mail->Body = "You recieved a message through the contact form of your website, gabormajerszky.com. <br> Here are the details: <br><br> Name: $name <br> Email: $email <br> Phone: $phone <br><br> $message";
  $mail->send();
} catch (Exception $e) {
  http_response_code(500);
}
  
?>
