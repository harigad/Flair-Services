$flair.home = {
	backGroundColor: ["#dddddd","#eeeeee"],
	
    init: function(){
	  $flair.login.init(this.icons);
	},
	
	
	icons: function() {
	
	  	var str = "<div style='text-align:center;' >";
		
		  str +="<div id='notifications' ></div>";
		
		str += "<a onclick=\"$flair.go.refreshHomePlaces();\" ><div  class='flair_thumb' style='background-color:#eee;' >nearby</div></a>";
			str += "<a href='#page=friends&title=friends&id=friends' ><div  style='background-color:#ddd;'  class='flair_thumb' >friends</div></a>";
			str += "<a href=\"#page=user&title=" + $flair.login.user.name + "&id=" + $flair.login.user.id + "\" ><div  style='background-color:#eee;' class='flair_thumb' >me</div></a>";
	
		
		str +="<div onclick='$flair.flair.init(1);' class='flair_thumb' style='background-color:#ddd;background-image:url(images/icons/1.png);background-position:center center;' ></div>";
		str +="<div onclick='$flair.flair.init(2);'  class='flair_thumb' style='background-color:#eee;background-image:url(images/icons/2.png);background-position:center center;' ></div>";
		str +="<div onclick='$flair.flair.init(3);'  class='flair_thumb' style='background-color:#ddd;background-image:url(images/icons/3.png);background-position:center center;' ></div>";
		str +="<div onclick='$flair.flair.init(4);'  class='flair_thumb' style='background-color:#eee;background-image:url(images/icons/10.png);background-position:center center;' ></div>";
		str +="<div onclick='$flair.flair.init(5);'  class='flair_thumb' style='background-color:#ddd;background-image:url(images/icons/11.png);background-position:center center;' ></div>";
		str +="<div onclick='$flair.flair.init(6);'  class='flair_thumb' style='background-color:#eee;background-image:url(images/icons/seafood.png);background-position:center center;' ></div>";
		str +="<div onclick='$flair.flair.init(7);'  class='flair_thumb' style='background-color:#ddd;background-image:url(images/icons/3.png);background-position:center center;' ></div>";
		str +="<div onclick='$flair.flair.init(8);'  class='flair_thumb' style='background-color:#eee;background-image:url(images/icons/2.png);background-position:center center;' ></div>";
		str +="<div onclick='$flair.flair.init(9);'  class='flair_thumb' style='background-color:#ddd;background-image:url(images/icons/1.png);background-position:center center;' ></div>";
		
		str += "</div>";
		
			
		$flair.go.updateHistory("home","Flair","home");		
		$flair.window.printPage(str);
		
		$flair.notifications.init();
	
	
	
	},
	
	
	
	friendsFeed: function() {
	var str="<div style='text-align:center;' >";						
		
	str += "<a onclick=\"$flair.go.refreshHomePlaces();\" ><div  class='flair_thumb' >refresh</div></a>";
			str += "<a onclick=\"$flair.flair.init();\" ><div class='flair_thumb' >flair</div></a>";
			str += "<a href=\"#page=user&title=me&id=me\" ><div class='flair_thumb' >me</div></a>";
			
		str += "<div id='flairs_holder' style='margin-top:5px;' >";	
			for(var i=0;i<21;i++) {		
				bgColor=this.backGroundColor[i];
			//	str += "<div class='flair_thumb' style='color:#fff;background-color:" + bgColor + ";position:relative;' ></div>";
			}		
		str += "</div>";
		
		$flair.go.updateHistory("home","Flair","home");		
		$flair.window.printPage(str);
	
		if($flair.user.friendsFlairs){
		  $flair.go.placeFlairs($flair.user.friendsFlairs);
		  $flair.window.print("home_refresh","refresh");
		}else{
		  $flair.user.friends();		
		}
		
}	

}