<?php

include_once 'db_connect.php';
include_once 'functions.php';

$sql = "
SELECT * FROM password_reset
WHERE selector = '" . $_POST['selector'] . "' AND expires >= " . time();
$result = $mysqli->query($sql);
if ($result->num_rows == 0) {
  header('Location: ../error.php?err=Token has expired. Please try to reset again.');
  exit();
}

$row = $result->fetch_assoc();
$auth_token = $row['token'];
$calc = hash('sha256', hex2bin($_POST['validator']));

if (hash_equals($calc, $auth_token) )  {
    $email = $row['email'];

    $sql = "
    SELECT * FROM members WHERE email = '" . $email . "'";
    $result = $mysqli->query($sql);
    if ($result->num_rows == 0) {
      header('Location: ../error.php?err=An unexpected error occured.');
      exit();
    }
    $salt = $result->fetch_assoc()['salt'];
    $password = hash('sha512', $_POST['password'] . $salt);

    $sql = "
    UPDATE members SET password = '" . $password . "'
    WHERE email = '" . $email . "'";
    if ($mysqli->query($sql) === TRUE) {
      ;
    } else {
      echo $mysqli->error;
    }

    if (login_check($mysqli)) {
      session_destroy();
    }
    echo "Password updated successfully";
}
