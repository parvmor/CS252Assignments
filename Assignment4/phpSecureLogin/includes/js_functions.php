<?php

include_once 'db_connect.php';

header('Content-Type: application/json');
$response = array();
if (!isset($_POST['callee'])) {
  $response['error'] = 'No function to call!';
}
if (!isset($_POST['args'])) {
  $response['error'] = 'No arguments to pass!';
}
if (!isset($response['error'])) {
  switch ($_POST['callee']) {
    case 'is_username_present':
      $response['present'] = is_username_present($_POST['args']);
      if ($response['present']) {
        $response['suggestions'] = suggest_usernames($_POST['args']);
      }
      break;
    default:
      $response['error'] = $_POST['callee'] . ' is not a valid function!';
      break;
  }
}

echo json_encode($response);

function is_username_present($username) {
  $mysqli = $GLOBALS['mysqli'];
  if ($stmt = $mysqli->prepare('SELECT id FROM members WHERE username = ?')) {
    $stmt->bind_param('s', $username);
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

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function suggest_usernames($username) {
  $suggestions = array();
  for ($i = 0; $i < 7; $i += 1) {
    $suggestions[$i] = $username . '_' . generateRandomString(4);
    while (is_username_present($suggestions[$i])) {
      $suggestions[$i] = $username . '_' . generateRandomString(4);
      break;
    }
  }
  return $suggestions;
}

?>
