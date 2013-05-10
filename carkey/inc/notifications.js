$flair.notifications = {


  init: function() {
  
	 var url="search.php";
	 var data="&type=notifications";
		
		 
		 if($flair.user.accessToken){
		  data+="&accessToken=" + $flair.user.accessToken;	
		 }
		
		 if($flair.notificationsRequest)
		 {
				$flair.notificationsRequest.abort();
		 }		
		 
		 var that = this;
		 
		  $flair.notificationsRequest=$.ajax({
			type: "POST",
			url: $flair.go.domain + url,
			data:data,
			dataType: "json",
			success: function(t){
			  var str="";
			  for(var i=0;i<t.length;i++){
			    str = "<a href=\"" + t[i].uri + "\" ><div class='flair_thumb' style='line-height:48px;height:48px;margin-left:auto;margin-right:auto;width:286px;vertical-align:middle;display:block;background-color:#fff;color:#6996F5;' ><img src='images/oscar_48.png' style='vertical-align:middle;' >" + t[i].title + "</div></a>";
			  }
			  
			  $('#notifications').hide();
			  $flair.window.print("notifications",str);
			  $('#notifications').slideDown();
			}

		});
  }

}