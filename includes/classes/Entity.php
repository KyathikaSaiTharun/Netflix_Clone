<?php
class Entity{
  private $con, $sqlData;
  public function __construct($con,$input){ //assign this values to the above private variables
  $this->con=$con;
  if(is_array($input)){       //if inputis an array dont search the database,just assign it to sqldata variable
      $this->sqlData=$input;
  }
  else{
    $query=$this->con->prepare("SELECT * FROM entities WHERE id=:id");   //if input isnt a array,select the data corresponding to the input id form the entities table
    $query->bindValue(":id",$input);
    $query->execute();

    $this->sqlData=$query->fetch(PDO::FETCH_ASSOC);    //to get data from sqlquery and store it in a associative array or key value array .then this array is assigned to sqldata variable
  }

}
public function getId(){
  return $this->sqlData["id"];
}
public function getName(){
  return $this->sqlData["name"];
}
public function getThumbnail(){
  return $this->sqlData["thumbnail"];
}
public function getPreview(){
  return $this->sqlData["preview"];
}
public function getCategoryId(){
  return $this->sqlData["categoryId"];
}
public function getSeasons(){
  $query=$this->con->prepare("SELECT * FROM videos WHERE entityId=:id
                              AND isMovie=0 ORDER BY season,episode ASC");
  $query->bindValue(":id",$this->getId());        //select videos of tv shows, in ascending order of seasons and episodes
  $query->execute();

   $seasons=array();
   $videos=array();
   $currentSeason=null;
   while($row=$query->fetch(PDO::FETCH_ASSOC)){
     if($currentSeason!=null && $currentSeason!= $row["season"]){
       $seasons[]=new Season($currentSeason,$videos);    //this array will conatin season number the videos in that season
       $videos=array();    //clearing the videos array
         }
     $currentSeason=$row["season"];    //RHS returns 1 =>season1
     $videos[]=new Video($this->con, $row);
   }
   if(sizeof($videos)!=0){    //for the last row while loop wont work.hence this step(corner case)
     $seasons[]=new Season($currentSeason,$videos);

   }
   return $seasons;
}
}
 ?>
