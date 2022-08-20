<?php      //to coonect to the database
ob_start();  //it turns on output buffering
session_start();
date_default_timezone_set("Asia/Kolkata");

try {               //con is a connection variable
  $con=new PDO("mysql:dbname=netchill;host=localhost","root","");   //root is username and empty string is password by default for this local host.
  $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
  exit("Connection failed:" . $e->getMessage());    //exit function stops all the php code execution and returns the content inside the brackets.
}

 ?>
