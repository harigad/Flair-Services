$flair.comments = {

  onClick: function(){
    $("#textarea").show();
    $(".headerBg").hide(); 
	$("#comments_input").focus(); 
  },
  
  hide: function(){
    $("#textarea").hide();
    $(".headerBg").show(); 
  }
  
}