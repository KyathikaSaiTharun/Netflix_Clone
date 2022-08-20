
<?php    //to resume playing where we left off.
  require_once("../includes/config.php");   //  ../ is to goback into the netchill folder
  if(isset($_POST["videoId"]) && isset($_POST["username"])){
    $query=$con->prepare("SELECT progress FROM videoProgress WHERE username=:username AND videoId=:videoId");
    $query->bindValue(":username",$_POST["username"]);
    $query->bindValue(":videoId",$_POST["videoId"]);
    $query->execute();

    echo $query->fetchColumn();  //returs the progress value i.e., the time at which the video is playing currently
  }
  else{
    echo "No videoId or username passed into file";
  }
 ?>
