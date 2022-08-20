<?php    //to provide preview for the selected entity in the homepage
require_once("includes/header.php");

if(!isset($_GET["id"])){
  ErrorMessage::show("No ID passed into page");
}
$entityId=$_GET["id"];   //gets id from the url
$entity=new Entity($con,$entityId);

$preview=new PreviewProvider($con,$userloggedIn);
echo $preview->createPreviewVideo($entity);

$seasonProvider=new SeasonProvider($con,$userloggedIn);
echo $seasonProvider->create($entity);

$categoryContainers=new CategoryContainers($con,$userloggedIn);
echo $categoryContainers->showCategory($entity->getCategoryId(),"You might also like");

 ?>
