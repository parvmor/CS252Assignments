<?php

include_once 'db_connect.php';
include_once 'psl-config.php';

$sql = "
CREATE TABLE IF NOT EXISTS password_reset (
  ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255),
  selector CHAR(16),
  token CHAR(64),
  expires BIGINT(20))
";
if ($mysqli->query($sql) === TRUE) {
  ;
} else {
  echo $mysqli->error;
}

$email = $_POST['email'];
if (is_email_present($email)) {
  ;
} else {
  header('Location: ../error.php?err=The requested email is not registered');
  exit();
}

$selector = bin2hex(random_bytes(8));
$token = random_bytes(32);
$url = sprintf('%sreset_password.php?%s', "http://localhost:3000/includes/", http_build_query([
    'selector' => $selector,
    'validator' => bin2hex($token)
]));
$expires = new DateTime('NOW');
$expires->add(new DateInterval('PT01H'));
$sql = "
DELETE IGNORE FROM password_reset
  WHERE email = '" . $email . "'";
if ($mysqli->query($sql) === TRUE) {
  ;
} else {
  echo $mysqli->error;
}

$sql = "
  INSERT INTO password_reset (email, selector, token, expires)
  VALUES ('" . $email . "','"
            . $selector . "','"
            . hash('sha256', $token) . "','"
            . $expires->format('U') . "')";
if ($mysqli->query($sql) === TRUE) {
  ;
} else {
  echo $mysqli->error;
}

$message = '<p>Note: This mail is generated as part of CS252 assignment. Please ignore the mail if not relevant. If you are being spammed please contact at parv@iitk.ac.in</p>';
$message .= '<p>We recieved a password reset request. The link to reset your password is below.';
$message .= 'The link will expire in an hour. If you did not make this request, you can ignore this email</p>';
$message .= '<p>Here is your password reset link:</br>';
$message .= sprintf('<a href="%s">%s</a></p>', $url, $url);
$message .= '<p>Thanks!</p>';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './vendor/autoload.php';
$mail = new PHPMailer(true);
try {
  $mail->isSMTP();
  $mail->Host = 'smtp.cc.iitk.ac.in';
  $mail->SMTPAuth = true;
  $mail->Username = MAIL_USER;
  $mail->Password = MAIL_PASS;
  $mail->Port = 25;

  //Recipients
  $mail->setFrom('noreply@iitk.ac.in', 'No Reply');
  $mail->addAddress($email);
  $mail->addReplyTo('noreply@login.com');

  $mail->isHTML(true);
  $mail->Subject = 'Your password reset link';
  $mail->Body    = $message;

  $mail->send();
  echo 'Password reset link has been sent';
} catch (Exception $e) {
    echo 'Mail could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

// $sent = mail($to, $subject, $message, $headers);

function is_email_present($email) {
  $mysqli = $GLOBALS['mysqli'];
  if ($stmt = $mysqli->prepare('SELECT id FROM members WHERE email = ?')) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->fetch();

    if ($stmt->num_rows == 0) {
      return false;
    }
    return true;
  } else {
    header('Location: ../error.php?err=Database error: cannot prepare statement');
    exit();
  }
}

?>
