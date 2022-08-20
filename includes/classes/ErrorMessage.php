<?php    //to display error message
class ErrorMessage{
  public static function show($text){
    exit("<span class='errorBanner'>$text</span>");    //exit stops all the script execution.
  }
}
?>
