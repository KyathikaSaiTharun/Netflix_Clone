<?php
class CategoryContainers{      //to show categories in home page (action,comedy.....)
  private $con;
  private $username;
  public function __construct($con,$username){

    $this->con=$con;

    $this->username=$username;
  }

  public function showAllCategories(){
    $query=$this->con->prepare("SELECT * FROM categories");   //select all the elemnts from the categories folder
    $query->execute();

    $html="<div class='previewCategories'>";

    while($row=$query->fetch(PDO::FETCH_ASSOC)) {
      $html .=$this->getCategoryHtml($row,null,true,true);  // . is used to concatenate
    }                                                       //both trues since movies and tv shows are present in the homepage

    return $html . "</div>";
  }


  public function showTVShowCategories(){
    $query=$this->con->prepare("SELECT * FROM categories");   //select all the elemnts from the categories folder
    $query->execute();

    $html="<div class='previewCategories'>
            <h1>TV Shows</h1>";

    while($row=$query->fetch(PDO::FETCH_ASSOC)) {
      $html .=$this->getCategoryHtml($row,null,true,false);  // . is used to concatenate
    }                                                       //

    return $html . "</div>";
  }


  public function showMovieCategories(){
    $query=$this->con->prepare("SELECT * FROM categories");   //select all the elemnts from the categories folder
    $query->execute();

    $html="<div class='previewCategories'>
            <h1>Movies</h1>";

    while($row=$query->fetch(PDO::FETCH_ASSOC)) {
      $html .=$this->getCategoryHtml($row,null,false,true);  // . is used to concatenate
    }                                                       //

    return $html . "</div>";
  }
  public function showCategory($categoryId,$title=null){
    $query=$this->con->prepare("SELECT * FROM categories WHERE id=:id");
    $query->bindValue(":id",$categoryId);
    $query->execute();

    $html="<div class='previewCategories '>";

    while($row=$query->fetch(PDO::FETCH_ASSOC)) {
      $html .=$this->getCategoryHtml($row,$title,true,true);  // . is used to concatenate
    }                                                       //both trues since movies and tv shows are present in the homepage

    return $html . "</div>";
  }

  private function getCategoryHtml($sqlData,$title,$tvShows,$movies){
    $categoryId=$sqlData["id"];
    $title=$title == null? $sqlData["name"]:$title;  //if title is null take it from sqldata otherwise use the existing one.

    if($tvShows && $movies){
      $entities=EntityProvider::getEntities($this->con,$categoryId,30);   //show 30 movies and  tv shoes (entities) of a particular genre
    }
    else if($tvShows){
      //get tv show entities
      $entities=EntityProvider::getTVShowEntities($this->con,$categoryId,30);   //show 30 movies and  tv shoes (entities) of a particular genre

    }
    else{
      //get movie entities
      $entities=EntityProvider::getMoviesEntities($this->con,$categoryId,30);   //show 30 movies and  tv shoes (entities) of a particular genre

    }

    if(sizeof($entities)==0){
      return;
    }
    $entitiesHtml="";
  $previewProvider=new PreviewProvider($this->con,$this->username);
    foreach($entities as $entity) {
      $entitiesHtml.=$previewProvider->createEntityPreviewSquare($entity);   // entities are appeared,on homepage
    }
     return "<div class='category'>
          <a href='category.php?id=$categoryId'>
            <h3>$title</h3>
          </a>
          <div class='entities'>
            $entitiesHtml
          </div>
     </div>";
  }
}
?>
