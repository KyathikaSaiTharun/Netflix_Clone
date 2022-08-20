<?php

require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");

 $account =new Account($con);

 if(isset($_POST["submitButton"])){

    $username=FormSanitizer::sanitizeFormUsername($_POST["username"]);

     $password=FormSanitizer::sanitizeFormPassword($_POST["password"]);

    $success= $account->login($username,$password);
    if($success){  //store session           // the session is set when a user is registered or logged in
      $_SESSION["userloggedIn"]=$username;    //if this session variable is not set i.e,not equal to usename it will bring the user to the login page(code in index.php)
      header("Location: index.php");  //go to this page
    }



}
function getInputValue($name){     //To remember the last entered username .if the password or username is wrong, after submitting u can still see the username
  if(isset($_POST[$name])){
    echo $_POST[$name];
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Netchill</title>
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <div class="signInContainer">
      <div class="column">
        <div class="header">
          <img src="assets/images/logo.png" title="Logo"alt="Company logo">
          <h3>Sign In</h3>
          <span>to continue to Netchill</span>
        </div>
        <form class=""  method="POST">
          <?php echo $account->getError(Constants::$loginFailed); ?>

          <input type="text" name="username" placeholder="User Name"value="<?php getInputValue("username"); ?>" required>


          <input type="password" name="password" placeholder="Password"value="" required>

          <input type="submit" name="submitButton" value="SUBMIT">

        </form>
        <a href="register.php"class="signInMessage">Don't have an account? Sign up here! </a>

      </div>

    </div>

  </body>
</html>
