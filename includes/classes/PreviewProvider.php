<?php
class PreviewProvider{
  private $con;
  private $username;
  public function __construct($con,$username){

    $this->con=$con;

    $this->username=$username;
  }
  public function createCategoryPreviewVideo($categoryId){
    $entitiesArray=EntityProvider::getEntities($this->con,$categoryId,1);

   if(sizeof($entitiesArray)==0){
     ErrorMessage::show("No TV shows to display");
   }
   return $this->createPreviewVideo($entitiesArray[0]);
  }


  public function createTVShowPreviewVideo(){
    $entitiesArray=EntityProvider::getTVShowEntities($this->con,null,1);

   if(sizeof($entitiesArray)==0){
     ErrorMessage::show("No TV shows to display");
   }
   return $this->createPreviewVideo($entitiesArray[0]);
  }


  public function createMoviesPreviewVideo(){
    $entitiesArray=EntityProvider::getMoviesEntities($this->con,null,1);

    if(sizeof($entitiesArray)==0){
         ErrorMessage::show("No Movies to display");
    }
    return $this->createPreviewVideo($entitiesArray[0]);
  }

  public function createPreviewVideo($entity){
    if($entity==null){
      $entity=$this->getRandomEntity();
    }
    $id=$entity->getId();
    $name=$entity->getName();
    $preview=$entity->getPreview();
    $thumbnail=$entity->getThumbnail();


    $videoId=VideoProvider::getEntityVideoForuser($this->con,$id,$this->username);
    $video=new Video($this->con,$videoId);   //to show subtitle(season and episode number) in the homepage at the play button

    $inProgress=$video->isInProgress($this->username);
    $playButtonText=$inProgress? "Continue Watching" : "Play";

    $seasonEpisode=$video->getSeasonAndEpisode();
    $subHeading=$video->isMovie()? "" : "<h4>$seasonEpisode</h4>";  //if it is a movie return empty string else return season and episode numbers

    return "<div class='previewContainer'>

         <img src='$thumbnail' class='previewImage' hidden >      <!--we want the image to be displayed only after the preview video ends-->

         <video autoplay muted class='previewVideo' onended='previewEnded()'>          <!--video palys automatically with no sound-->
           <source src='$preview' type='video/mp4' >
         </video>

         <div class='previewOverlay'>
           <div class='mainDetails'>
           <h3>$name</h3>
           $subHeading

           <div class='buttons'>
           <button onclick='watchVideo($videoId)'><i class='fa-solid fa-play'></i> $playButtonText</button>
           <button onclick='volumeToggle(this)'><i class='fa-solid fa-volume-xmark'></i></button>

           </div>
           </div>
         </div>
    </div>";
  }

  public function createEntityPreviewSquare($entity){
    $id=$entity->getId();
    $thumbnail=$entity->getThumbnail();
    $name=$entity->getName();

    return "<a href='entity.php?id=$id'>
          <div class='previewContainer small'>
          <img src='$thumbnail' title='$name' >
          </div>
    </a>";
  }


  private function getRandomEntity(){
    $query=$this->con->prepare("SELECT * FROM entities ORDER BY RAND() LIMIT 1");   //select 1 random element from the entities table.
    $query->execute();
    $row=$query->fetch(PDO::FETCH_ASSOC);   //to get data from sqlquery and store it in a associative array or key value array
  //  echo $row["name"];    //name is a column name in entities table
     return new Entity($this->con,$row);

     /*instead of writing this code
     $entity=EntityProvider::getEntities($this->con,null,1);
     return $entity[0];  //since we need only 1 video preview   */
    }
} ?>
