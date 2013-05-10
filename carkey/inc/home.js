$flair.home = {
	backGroundColor: ["#dddddd","#eeeeee"],
	
    init: function(){
       $flair.user.state="";
       $flair.go.updateHistory("home","","home");
       $flair.login.init(this.print);      		
	},
	
	print: function(){	
		   $flair.go.updateHistory("home","<img src='images/logo_small.png'  >","home");
		  var str="<div style='text-align:center;padding-top:15px;background-color:#eee' >";						
		
			str += "<a onclick=\"$flair.go.refreshHomePlaces();\" ><div  class='flair_thumb' >G35</div></a>";
			str += "<a onclick=\"$flair.flair.init();\" ><div class='flair_thumb' >friends</div></a>";
			str += "<a href=\"#page=user&title=" + $flair.login.user.name + "&id=" + $flair.login.user.id + "&photo=" + $flair.login.user.photo_big +  "\" ><div class='flair_thumb' >me</div></a>";
			
		str += "<div id='flairs_holder' style='margin-top:5px;' >";	
				
		str += "</div>";
		
		$flair.window.printPage(str);
		 
	    //  $flair.home.friendsFeed();	    	
	},
	
		
	friendsFeed: function() {
	
		if($flair.user.friendsFlairs){
		  $flair.go.placeFlairs($flair.user.friendsFlairs);
		  $flair.window.print("home_refresh","refresh");
		}else{
		  $flair.user.friends();		
		}
		
}	

}