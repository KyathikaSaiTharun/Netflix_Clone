$(document).scroll(function(){    //to get a black background to the navigation bar on homepage when scrolled down
  var isScrolled=$(this).scrollTop()>$(".topBar").height();  //if the top page(in the homepage) is higher than the navigation bar set .topBar class as Scrolled
  $(".topBar").toggleClass("scrolled",isScrolled);
})


function volumeToggle(button){
  var muted =$(".previewVideo").prop("muted");
  $(".previewVideo").prop("muted",!muted);   //this function mutes if the preview video is unmuted and vice versa.

  $(button).find("i").toggleClass("fa-volume-xmark");    //to change mute and unmute icons
    $(button).find("i").toggleClass("fa-volume-high");    //it means if one icon is gone another appears
}

function previewEnded(){
  $(".previewVideo").toggle();    //to show preview image when the preview video is ended
  $(".previewImage").toggle();      //when video stops the video is hidden (toggled) and when the image is toggled image becomes unhidden
}
   //used in watch.php
function goBack(){     //to goback to the prev page from the video player through the back button
   window.history.back();
}
function startHideTimer(){
  var timeout=null;
                                          //Jquery inbuilt functions
  $(document).on("mousemove",function(){   //when a user moves the mouse or cursor the video controls appears. Also it clears the timer
    clearTimeout(timeout);                   //execute the function when the mouse moves.
    $(".watchNav").fadeIn();

    timeout=setTimeout(function(){     //when the cursor is un moved for 2 seconds the controls dissappear.
      $(".watchNav").fadeOut();
    },2000);
  })
}

function initVideo(videoId,username){
  startHideTimer();
  setStartTime(videoId,username);
  updateProgressTimer(videoId,username);      //to update where u left off the video
}
function updateProgressTimer(videoId,username){
  addDuration(videoId,username);

  var timer;
  $("video").on("playing",function(event){    //whe the video is playing log the progress(time of video) for every 3 seconds
    window.clearInterval(timer);
    timer=window.setInterval(function(){
      updateProgress(videoId,username,event.target.currentTime);
    },3000);
  })
  .on("ended",function(){
    setFinished(videoId,username);
    window.clearInterval(timer);
  })

}
function addDuration(videoId,username){
  $.post("ajax/addDuration.php",{videoId: videoId,username: username},function(data){       //goes to ajax folder and post the contents in the file i.e.,the contents in the ajax files will be the function(data)
    if(data!==null && data !==""){
    alert(data);
  }
  })

}
function updateProgress(videoId,username,progress){
  $.post("ajax/updateDuration.php",{videoId: videoId,username: username,progress: progress },function(data){       //goes to ajax folder and post the contents in the file i.e.,the contents in the ajax files will be the function(data)
    if(data!==null && data !==""){
      //alert(data);
  }
  })
}
function setFinished(videoId,username){
  $.post("ajax/setFinished.php",{videoId: videoId,username: username},function(data){       //goes to ajax folder and post the contents in the file i.e.,the contents in the ajax files will be the function(data)
    if(data!==null && data !==""){
    //alert(data);
  }
  })
}
function setStartTime(videoId,username){
  $.post("ajax/getProgress.php",{videoId: videoId,username: username},function(data){       //goes to ajax folder and post the contents in the file i.e.,the contents in the ajax files will be the function(data)
  if(isNaN(data)){
    alert(data);
    return;
  }
  $("video").on("canplay",function(){      //"this" refers to video
    this.currentTime=data;                //sets the current time of the video to data.
    $("video").off("canplay");
  })

  })
}
function restartVideo(){
  $("video")[0].currentTime=0;   //[] in jquery is nothing but accesing javascript objects
  $("video")[0].play();   //when the replay button is clicked fade out the next episode info
  $(".upNext").fadeOut();
}
function watchVideo(videoId){
  window.location.href="watch.php?id=" + videoId;   //to play the next epsiode (play button)
}
function showUpNext(){
  $(".upNext").fadeIn();
}
