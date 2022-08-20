<?php   //to provide upnext videos after completing a video
class VideoProvider{
  public static function getUpNext($con,$currentVideo){
    $query=$con->prepare("SELECT * FROM videos                               /*selecting videos which is not the current video.the video must be next episode if its a season or play next nexxt season if the season completes*/
                          WHERE entityId=:entityId AND id!=:videoId
                          AND (
                            (season=:season AND episode>:episode) OR season>:season
                          )
                          ORDER BY season, episode ASC LIMIT 1");
    $query->bindValue(":entityId",$currentVideo->getEntityId());
    $query->bindValue(":season",$currentVideo->getSeasonNumber());
    $query->bindValue(":episode",$currentVideo->getEpisodeNumber());
    $query->bindValue(":videoId",$currentVideo->getId());

    $query->execute();
                                          //if there is no next episode in the season select the most viewed episode or movie
    if($query->rowCount()==0){
      $query=$con->prepare("SELECT * FROM videos WHERE season<=1 AND episode<=1
                          AND id!=:videoId ORDER BY views DESC LIMIT 1");

      $query->bindValue(":videoId",$currentVideo->getId());
      $query->execute();
    }
    $row=$query->fetch(PDO::FETCH_ASSOC);
    return new Video($con,$row);
  }

//for the play button in the homepge to show continue watching
public static function getEntityVideoForuser($con,$entityId,$username){
  $query=$con->prepare("SELECT videoId FROM videoProgress
                       INNER JOIN videos
                       ON videoProgress.videoId=videos.id
                       WHERE videos.entityId=:entityId
                       AND videoProgress.username=:username
                       ORDER BY videoProgress.dateModified DESC
                       LIMIT 1 ");
  $query->bindValue(":entityId",$entityId);
  $query->bindValue(":username",$username);
  $query->execute();

  if($query->rowCount()==0){   //if user didnt start watching the video yet show him the first episode
    $query=$con->prepare("SELECT * FROM videos
                         WHERE entityId=:entityId
                         ORDER BY season, episode ASC LIMIT 1");
  $query->bindValue(":entityId",$entityId);
  $query->execute();
  }
  return $query->fetchColumn();

}
}

 ?>
