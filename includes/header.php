<?php
require_once("includes/config.php");
require_once("includes/classes/PreviewProvider.php");
require_once("includes/classes/Entity.php");
require_once("includes/classes/EntityProvider.php");
require_once("includes/classes/CategoryContainers.php");
require_once("includes/classes/ErrorMessage.php");
require_once("includes/classes/Seasonprovider.php");
require_once("includes/classes/Season.php");
require_once("includes/classes/Video.php");
require_once("includes/classes/VideoProvider.php");
require_once("includes/classes/User.php");

if(!isset($_SESSION["userloggedIn"])){     //if session isn't set it will the user to register page
  header("Location: register.php");
}
$userloggedIn=$_SESSION["userloggedIn"];

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Netchill</title>
    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/cb9ffbddad.js" crossorigin="anonymous"></script>

    <script src="assets/js/script.js"></script>
  </head>
  <body>                   <!--these html elements has no closing tags. closing tags are created by browser itself-->
    <div class="wrapper">

 <?php
 if(!isset($hideNav)){
   include_once("includes/navBar.php");
 } ?>
