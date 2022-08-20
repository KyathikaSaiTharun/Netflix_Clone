
<?php
require_once("includes/header.php");

$preview=new PreviewProvider($con,$userloggedIn);
echo $preview->createMoviesPreviewVideo();  //To show the preview of random movies when refreshed everytime


$containers=new CategoryContainers($con,$userloggedIn);
echo $containers->showMovieCategories();
 ?>
