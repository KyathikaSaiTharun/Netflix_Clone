<?php
require_once("includes/header.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");

$detailsMessage="";
$passwordMessage="";
if(isset($_POST["saveDetailsButton"])){
  $account=new Account($con);

  $firstName=FormSanitizer::sanitizeFormString($_POST["firstName"]);
  $lastName=FormSanitizer::sanitizeFormString($_POST["lastName"]);
  $email=FormSanitizer::sanitizeFormString($_POST["email"]);

  if($account->updateDetails($firstName,$lastName,$email,$userloggedIn)){
    $detailsMessage="<div class='alertSuccess'>
    Details updated Successfully!
    </div>";

  }
  else{
    //failure
    $errorMessage=$account->getFirstError();
    $detailsMessage="<div class='alertError'>
        $errorMessage
    </div>";
  }
}

if(isset($_POST["savePasswordButton"])){
  $account=new Account($con);

  $oldPassword=FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
  $newPassword=FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
  $newPassword2=FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

  if($account->updatePassword($oldPassword,$newPassword,$newPassword2,$userloggedIn)){
    $passwordMessage="<div class='alertSuccess'>
    Password updated Successfully!
    </div>";

  }
  else{
    //failure
    $errorMessage=$account->getFirstError();
    $passwordMessage="<div class='alertError'>
        $errorMessage
    </div>";
  }
}


 ?>

 <div class="settingsContainer column">

   <div class="formSection">
     <form class=""  method="POST">
       <h2>User Details</h2>

       <?php
       $user=new User($con,$userloggedIn);

       $firstName=isset($_post["firstName"]) ? $_post["firstName"] : $user->getFirstName();  //if a user enters the name now return that name .Else show the name that was stored in the database
       $lastName=isset($_post["lastName"]) ? $_post["lastName"] : $user->getLastName();
       $email=isset($_post["email"]) ? $_post["email"] : $user->getEmail();


          ?>
       <input type="text" name="firstName" placeholder="First Name" value="<?php echo $firstName; ?>">
       <input type="text" name="lastName" placeholder="Last Name" value="<?php echo $lastName; ?>">
       <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>">

       <div class="message">
         <?php echo $detailsMessage;  ?>

       </div>

       <input type="submit" name="saveDetailsButton" value="Save">

     </form>

   </div>

   <div class="formSection">
     <form class=""  method="POST">
       <h2>Update Password</h2>
       <input type="password" name="oldPassword" placeholder="Old Password" value="">
       <input type="password" name="newPassword" placeholder="New Password" value="">
       <input type="password" name="newPassword2" placeholder="Confirm New Password" value="">
       <div class="message">
         <?php echo $passwordMessage;  ?>

       </div>
       <input type="submit" name="savePasswordButton" value="Save">

     </form>

   </div>

 </div>
