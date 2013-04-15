$flair.thumbs = {

  print: function(flair,i,length){
 		var images=[];
 		
 		var link;
 		link = false;
 		
 		if(flair.approved || flair.user===$flair.login.user.id){
 			link=true;
 		}
 		
			
			if(link){
			   
			   var foodColor = "#666";
			   if(flair.recipient >0 ){
			   
			   var peopleColor = "#6996F5";
			   
			   }else{
			   
			   var peopleColor = "#6996F5";
				
			   }	
			}else{
			
		       var foodColor = "#666666";
			   var peopleColor = "#333";
			
			}
	
			var thumbs = $tree.flairs;
			var icon = flair.type + ".png";
			var borderWidth=1;
			
			
			if(length==(i+1)){			  
			  var borderWidth=0;
			  
			}
			
			
			var str  = "<div style='border:0px solid #eee;border-bottom-width:" + borderWidth + "px;vertical-align:top;position:relative;' >";		
					if(link){
					str +="<a href=\"#page=user&title=" + flair.username + "&id=" + flair.user + "\" >";
					}
					str +="<div class='flair_thumb left_thumb'  id='img_" + flair.sid + "'  ></div>";
					if(link){
					str +="</a>";
					}
					  
						str +="<div class='flair_thumb right_thumb' style='height:auto;background-color:#fff;color:#666;padding:10px;min-height:80px;text-align:left;line-height:1.0em;vertical-align:top;' >";
						//if(link){
						str += "<a href=\"#page=user&title=" + flair.username + "&id=" + flair.user + "&userPhoto=" + flair.userphotobig + "\" >";
						//}
						str += "<div style='height:30px;white-space: nowrap;overflow:hidden;font-size:2.9em;font-family:verdana;color:#6996F5;padding-bottom:0px;' ><img src='" + flair.userphoto + "' style='width:30px;margin-right:0px;margin-bottom:0px;' ><span style='opacity:0.3;' >" + flair.username.split(" ")[0] + "</span></div>";
						//if(link){
						str += "</a>";
					   // }
							str += "<div style='font-size:1.2em;' >";				
								if(link){
								 str += "<a href=\"#page=place&title=" + flair.placename + "&id=" + flair.pid + "&type=" + flair.type + "&place=" + flair.placename + "&pid=" + flair.pid + "\" >";
								}
								str += "<span style='font-weight:normal;color:#333;color:" + foodColor + ";line-height:1.4em;' >" + flair.name + "</span>";
								if(link){
								  str += "</a>";
								}
								//str += "<span style='color:#666;font-weight:normal;' > by </span>";
								if(link){
								str += "<a href=\"#page=user&title=" + flair.recipientname + "&id="+ flair.recipient + "&pid=" + flair.pid + "&placename=" + flair.placename + "\" >";
								}
								str += "<span style='color:" + peopleColor + ";font-weight:normal;' > by " + flair.recipientname + "</span>";
								if(link){
								str += "</a>";
								}
								str += "<span style='font-weight:normal;color:#666;text-transform:lowercase;' > near " + flair.city + "</span>";	
								
						//	str += "<div style='padding-top:10px;text-align:left;font-size:0.8em;color:#6996F5;' ><span>like</span><span style='padding-left:10px;' >comment</span>";
							
							var like = "<div style='padding-top:10px;text-align:left;font-size:0.8em;color:#6996F5;' ><span id=\"like_div_" + flair.sid + "\" onclick=\"$flair.likes.onClick(" + flair.sid + ");\"'>like</span>";
							
							if(flair.user==$flair.login.user.id){
							  str +=like + "<span style='padding-left:10px;color:#990000;' >delete</span>";
							}else if(flair.recipient==$flair.login.user.id){
							  str +=like + "<span style='padding-left:10px;color:#990000;' >ignore</span>";
							}else{
							 str +=like;
							}							
							
							
							//edited
							//str += "<div id=\"Commentdiv\" style='padding-top:10px;text-align:left;font-size:0.8em;color:#6996F5;'><span id=\"CommentId\" style=\"padding-top:0px\"></span><table width=\"200\" id=\"CommentTab\" style='padding-top:0px;text-align:left;' ></table></div>";
							
							
							str +="</div>";
						
							str += "</div>";
						str += "</div>";
					
			
			
	
			
			str += "</div>";
			
			var thisImage = {};
			thisImage['id'] = flair.sid;
			thisImage['src'] = "images/icons/" + flair.icon;			
			$flair.go.preLoadImages.push(thisImage);
			return str;
  
  
  
  
  }


}