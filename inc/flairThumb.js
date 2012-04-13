$flair.thumbs = {

  print: function(flair,i,length){
		var images=[];
			
			if(!flair.approved){
			   
			   var foodColor = "#6996F5";
			   var peopleColor = "#115599";
			
			}else{
			
		       var foodColor = "#666666";
			   var peopleColor = "#666666";
			
			}
	
			var thumbs = $tree.flairs;
			var icon = flair.type + ".png";
			var borderWidth=1;
			
			
			if(length==(i+1)){			  
			  var borderWidth=0;
			  
			}
			
			
			var str  = "<div style='border:0px solid #eee;border-bottom-width:" + borderWidth + "px;vertical-align:top;position:relative;' >";		
					str +="<a href=\"#page=user&title=" + flair.username + "&id=" + flair.user + "\" ><div class='flair_thumb left_thumb'  id='img_" + flair.sid + "'  ><div style='color:#999;padding-top:5px;' >" + flair.username.split(" ")[0] + "</div></div></a>";
					
					
						str +="<div class='flair_thumb right_thumb' style='height:auto;background-color:#fff;color:#999;padding:10px;min-height:80px;text-align:left;line-height:1.5em;vertical-align:top;' >";
					
							str += "<div style='font-size:1.2em;' >";
							//	str += "<div id='img_" + flair.sid + "' style='background-color:#990000;margin-left:10px;-moz-border-radius: 2px;overflow:hidden;-webkit-border-radius: 2px;float:right;height:50px;width:50px;' ></div>";
								
								str += "<a href=\"#page=place&title=" + flair.placename + "&id=" + flair.pid + "&type=" + flair.type + "&place=" + flair.placename + "&pid=" + flair.pid + "\" ><span style='color:#333;color:" + foodColor + ";line-height:1.4em;' >" + flair.name + "</span></a>";
								str += "<span style='color:#999;font-weight:normal;' ><i> by </i></span>";
								str += "<a href=\"#page=user&title=" + flair.recipientname + "&id="+ flair.recipient + "&pid=" + flair.pid + "&placename=" + flair.placename + "\" >";
								  str += "<span style='color:" + peopleColor + ";' >" + flair.recipientname + "</span>";
								str += "</a>";
								str += "<span style='color:#999;text-transform:lowercase;' > in " + flair.city + "</span>";	
								
							str += "<div style='padding-top:10px;text-align:left;font-size:0.8em;color:#6996F5;' ><span>like</span><span style='padding-left:10px;' >comment</span>";
							
							
							if(!flair.approved && flair.user==$flair.user){
							  //str +="<span style='padding-left:10px;color:#990000;' >remove</span>";
							}
							
							str +="</div>";
						
							str += "</div>";
						str += "</div>";
					
			
			
	
			
			str += "</div>";
			
			var thisImage = {};
			thisImage['id'] = flair.sid;
			thisImage['src'] = flair.userphoto;			
			$flair.go.preLoadImages.push(thisImage);
			return str;
  
  
  
  
  }








}