
<?php
require_once("includes/header.php");

if(!isset($_GET["id"])){
  ErrorMessage::show("No id passed to page");
}


$preview=new PreviewProvider($con,$userloggedIn);
echo $preview->createCategoryPreviewVideo($_GET["id"]);  //To show the preview of random movies when refreshed everytime


$containers=new CategoryContainers($con,$userloggedIn);
echo $containers->showCategory($_GET["id"]);
 ?>
