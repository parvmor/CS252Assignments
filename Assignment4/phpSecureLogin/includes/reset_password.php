<?php

$selector = filter_input(INPUT_GET, 'selector');
$validator = filter_input(INPUT_GET, 'validator');

if ( false !== ctype_xdigit( $selector ) && false !== ctype_xdigit( $validator ) ) :
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Reset Password</title>
        <link rel="stylesheet" href="../styles/main.css" />
        <script type="text/JavaScript" src="../js/sha512.js"></script>
        <script type="text/JavaScript" src="../js/forms.js"></script>
        <script type="text/JavaScript" src="../js/passwords.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
  <body>
    <h1>Reset your password</h1>
    <ul>
      <li>Passwords must be at least 6 characters long</li>
      <li>Passwords must contain
          <ul>
              <li>At least one upper case letter (A..Z)</li>
              <li>At least one lower case letter (a..z)</li>
              <li>At least one number (0..9)</li>
          </ul>
      </li>
    </ul>
    <form action="reset_process.php" method="post">
        <input type="hidden" name="selector" value="<?php echo $selector; ?>">
        <input type="hidden" name="validator" value="<?php echo $validator; ?>">
        <input type="password" class="text" name="password" placeholder="Enter your new password" required  oninput="password_strength(this.form.password.value);">
        <br>
        <div hidden id='passwordStrength'>
        </div>
        <br>
        <input type="button" class="submit" value="Submit" onclick='resetformhash(this.form);'>
    </form>
    <p><a href="index.php">Login here</a></p>
  </body>
</html>
<?php endif; ?>
