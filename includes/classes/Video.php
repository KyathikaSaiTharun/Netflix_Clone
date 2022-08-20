<?php
class Video{
  private $con, $sqlData, $entity;
  public function __construct($con,$input){ //assign this values to the above private variables
  $this->con=$con;
  if(is_array($input)){       //if inputis an array dont search the database,just assign it to sqldata variable
      $this->sqlData=$input;
  }
  else{
    $query=$this->con->prepare("SELECT * FROM videos WHERE id=:id");   //if input isnt a array,select the data corresponding to the input id form the videos table
    $query->bindValue(":id",$input);
    $query->execute();

    $this->sqlData=$query->fetch(PDO::FETCH_ASSOC);    //to get data from sqlquery and store it in a associative array or key value array .then this array is assigned to sqldata variable
  }
  $this->entity=new Entity($con,$this->sqlData["entityId"]);
}

public function getId(){
  return $this->sqlData["id"];

}
public function getTitle(){
  return $this->sqlData["title"];
}
public function getDescription(){
  return $this->sqlData["description"];
}
public function getFilePath(){
  return $this->sqlData["filePath"];
}
public function getThumbnail(){
  return $this->entity->getThumbnail();     //since video folder doesnt contain the thumbnail info we access it through entity
}
public function getEpisodeNumber(){
  return $this->sqlData["episode"];
}
public function getSeasonNumber(){
  return $this->sqlData["season"];
}
public function getEntityId(){
  return $this->sqlData["entityId"];
}

public function incrementViews(){             //to count the no of views of a particular video . Implementation in watch.php
  $query=$this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
  $query->bindValue(":id",$this->getId());
  $query->execute();
}
public function getSeasonAndEpisode(){
  if($this->isMovie()){
    return;
  }
  $season=$this->getSeasonNumber();
  $episode=$this->getEpisodeNumber();

  return "Season $season, Episode $episode";
}

public function isMovie(){
  return $this->sqlData["isMovie"]==1;
}
public function isInProgress($username){
  $query=$this->con->prepare("SELECT * FROM videoProgress
                             WHERE videoId=:videoId AND username=:username");       //select the videos that are not watched completely
$query->bindValue(":videoId",$this->getId());
$query->bindValue(":username",$username);
$query->execute();

return $query->rowCount()!=0;

}
public function hasSeen($username){
  $query=$this->con->prepare("SELECT * FROM videoProgress
                             WHERE videoId=:videoId AND username=:username
                             AND finished=1");       //select the videos that are  watched completely
$query->bindValue(":videoId",$this->getId());
$query->bindValue(":username",$username);
$query->execute();

return $query->rowCount()!=0;

}

} ?>
