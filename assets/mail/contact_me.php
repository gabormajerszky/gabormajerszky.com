<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "./PHPMailer/Exception.php";
require "./PHPMailer/PHPMailer.php";
require "./PHPMailer/SMTP.php";

if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["phone"]) || empty($_POST["message"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  exit();
}

$config = parse_ini_file("../../config.ini");
$credentials = parse_ini_file($config["path"]);
$username = $credentials["username"];
$password = $credentials["password"];

$name = strip_tags(htmlspecialchars($_POST["name"]));
$email = strip_tags(htmlspecialchars($_POST["email"]));
$phone = strip_tags(htmlspecialchars($_POST["phone"]));
$message = strip_tags(htmlspecialchars($_POST["message"]));

// Passing true enables exceptions
$mail = new PHPMailer(true);

try {

  // Generic settings
  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->Timeout = 15;

  // Provider specific settings
  $mail->Host = "smtp.gmail.com";
  $mail->Port = 587;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Username = $username;
  $mail->Password = $password;

  // Message settings
  $mail->CharSet = "UTF-8";
  $mail->setFrom($username, "gabormajerszky.com");
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
